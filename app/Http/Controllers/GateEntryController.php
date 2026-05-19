<?php

namespace App\Http\Controllers;

use App\Models\EgateEntryLog;
use App\Models\EgateLog as EgateData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GateEntryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $manualEntryEnabled = SettingController::isEnabled(1);
        $rfidLoginEnabled = SettingController::isEnabled(2);

        if (! $manualEntryEnabled && ! $rfidLoginEnabled) {
            return response()->json([
                'message' => 'Manual login and RFID login are currently disabled.',
            ], 403);
        }

        $validated = $request->validate([
            'student_id' => ['nullable', 'string', 'max:255'],
            'rfid' => ['nullable', 'string', 'max:255'],
        ]);

        $manualInput = trim((string) ($validated['student_id'] ?? ''));
        $rfidInput = trim((string) ($validated['rfid'] ?? ''));

        if ($manualInput !== '' && ! $manualEntryEnabled) {
            return response()->json([
                'message' => 'Manual login is currently disabled.',
            ], 403);
        }

        if ($rfidInput !== '' && ! $rfidLoginEnabled) {
            return response()->json([
                'message' => 'RFID login is currently disabled.',
            ], 403);
        }

        $lookup = trim((string) ($validated['rfid'] ?: $validated['student_id'] ?: ''));

        if ($lookup === '') {
            return response()->json([
                'message' => 'Scan an RFID or enter a student number / LRN first.',
            ], 422);
        }

        $student = EgateData::query()
            ->where('student_number', $lookup)
            ->orWhere('lrn', $lookup)
            ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(COALESCE(remarks, '{}'), '$.lrn')) = ?", [$lookup])
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'No matching student was found in egate_data.',
            ], 404);
        }

        $latestLog = EgateEntryLog::query()
            ->where(function ($query) use ($student) {
                $query
                    ->where('egate_data_id', $student->id)
                    ->orWhere('student_id', $student->student_number);
            })
            ->latest('id')
            ->first();

        $status = $latestLog?->status === 1 ? 0 : 1;
        $statusLabel = $status === 1 ? 'IN' : 'OUT';

        $log = DB::transaction(function () use ($student, $status, $statusLabel, $request) {
            $entryLog = EgateEntryLog::query()->create([
                'egate_data_id' => $student->id,
                'student_id' => $student->student_number,
                'status' => $status,
            ]);

            $student->forceFill([
                'status' => $statusLabel,
                'logged_at' => now(),
                'gate_name' => 'Welcome Gate',
                'ip_address' => $request->ip(),
            ])->save();

            return $entryLog;
        });

        return response()->json([
            'message' => 'Entry submitted successfully.',
            'log_id' => $log->id,
            'egate_data_id' => $student->id,
            'student_id' => $student->student_number,
            'status' => $status,
            'status_label' => $status === 1 ? 'Login' : 'Logout',
        ]);
    }
}
