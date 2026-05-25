<?php

namespace App\Http\Controllers;

use App\Models\EgateLog as EgateData;
use Carbon\CarbonImmutable;
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
            'status' => ['nullable', 'integer'],
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
            ->orWhere('rfid', $lookup)
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'No matching student was found in egate_data.',
            ], 404);
        }

        $status = (int) ($validated['status'] ?? 2);
        $loggedAt = CarbonImmutable::now(config('app.timezone'))->format('Y-m-d H:i:s');

        $logId = DB::transaction(function () use ($student, $status, $request, $loggedAt) {
            $entryLogId = DB::table('egate_logs')->insertGetId([
                'egate_data_id' => $student->id,
                'student_id' => $student->student_number,
                'status' => $status,
                'created_at' => $loggedAt,
                'updated_at' => $loggedAt,
            ]);

            return $entryLogId;
        });

        return response()->json([
            'message' => 'Entry submitted successfully.',
            'log_id' => $logId,
            'egate_data_id' => $student->id,
            'student_id' => $student->student_number,
            'status' => $status,
            'status_label' => match ($status) {
                1 => 'Time In',
                0 => 'Time Out',
                default => 'N/A',
            },
        ]);
    }
}
