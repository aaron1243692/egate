<?php

namespace App\Http\Controllers;

use App\Models\EgateLog;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EgateLogSyncController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'integer'],
            'url' => ['nullable', 'url'],
            'status' => ['nullable', 'string', 'max:10'],
            'gate_name' => ['nullable', 'string', 'max:255'],
        ]);

        $url = $validated['url'] ?? 'https://app.olpcc.online/api/admin/id-migrations/students';
        $schoolYearId = $validated['school_year_id'] ?? 77;
        try {
            $response = Http::withoutVerifying()
                ->acceptJson()
                ->timeout(30)
                ->get($url, [
                    'school_year_id' => $schoolYearId,
                ])
                ->throw();
        } catch (ConnectionException $exception) {
            return response()->json([
                'message' => 'Unable to reach the API.',
            ], 503);
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => 'API request failed.',
                'error' => $exception->getMessage(),
            ], 500);
        }

        $records = collect($response->json('data', []));
        $created = 0;
        $updated = 0;

        $synced = $records->map(function (array $student) use (&$created, &$updated) {
            $studentNumber = trim((string) ($student['student_id'] ?? ''));

            if ($studentNumber === '') {
                return null;
            }

            $studentName = trim((string) ($student['student_name'] ?? $student['name'] ?? ''));

            $attributes = [
                'lrn' => $student['lrn'] ?? null,
                'rfid' => $student['rfid'] ?? null,
                'name' => $studentName,
                'role' => $student['role'] ?? null,
                'email' => $student['email'] ?? null,
                'contact' => $student['contact'] ?? $student['contact_number'] ?? $student['guardian_contact_number'] ?? null,
                'sex' => $student['sex'] ?? null,
                'department' => $student['department'] ?? null,
                'course' => $student['course_name'] ?? null,
                'school_level' => $student['school_level'] ?? $student['year_level'] ?? null,
                'grade_level' => $student['grade_level'] ?? $student['school_level'] ?? $student['year_level'] ?? null,
                'image' => $student['image'] ?? $student['photo'] ?? $student['profile_photo_url'] ?? null,
            ];

            $log = EgateLog::query()->where('student_number', $studentNumber)->first();

            if ($log) {
                $log->fill($attributes)->save();
                $updated++;
            } else {
                $log = EgateLog::query()->create([
                    'student_number' => $studentNumber,
                    ...$attributes,
                ]);
                $created++;
            }

            return [
                'id' => $log->id,
                'student_number' => $log->student_number,
                'student_name' => $log->name,
            ];
        })->filter()->values();

        return response()->json([
            'message' => 'EGate logs synced successfully.',
            'fetched' => $records->count(),
            'created' => $created,
            'updated' => $updated,
            'synced' => $synced,
        ]);
    }

}
