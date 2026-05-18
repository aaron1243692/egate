<?php

namespace App\Http\Controllers;

use App\Models\EgateLog;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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
        $status = $validated['status'] ?? 'IN';
        $gateName = $validated['gate_name'] ?? 'API Sync';

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

        $synced = $records->map(function (array $student) use (&$created, &$updated, $status, $gateName, $request) {
            $studentNumber = trim((string) ($student['student_id'] ?? ''));

            if ($studentNumber === '') {
                return null;
            }

            [$lastName, $firstName, $middleName] = $this->splitStudentName((string) ($student['student_name'] ?? ''));

            $attributes = [
                'last_name' => $lastName,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'department' => $student['department'] ?? null,
                'course' => $student['course_name'] ?? null,
                'year_level' => $student['year_level'] ?? null,
                'grade_level' => $student['school_level'] ?? null,
                'status' => $status,
                'logged_at' => now(),
                'gate_name' => $gateName,
                'ip_address' => $request->ip(),
                'remarks' => json_encode([
                    'profile_id' => $student['profile_id'] ?? null,
                    'lrn' => $student['lrn'] ?? null,
                    'school_year' => $student['school_year'] ?? null,
                    'birthday' => $student['birthday'] ?? null,
                    'guardian_name' => $student['guardian_name'] ?? null,
                    'guardian_address' => $student['guardian_address'] ?? null,
                    'guardian_contact_number' => $student['guardian_contact_number'] ?? null,
                ], JSON_UNESCAPED_SLASHES),
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
                'student_name' => trim(implode(' ', array_filter([
                    $log->first_name,
                    $log->middle_name,
                    $log->last_name,
                ]))),
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

    /**
     * @return array{0:string,1:string,2:?string}
     */
    private function splitStudentName(string $studentName): array
    {
        $studentName = trim(preg_replace('/\s+/', ' ', $studentName) ?? '');

        if ($studentName === '') {
            return ['', '', null];
        }

        if (str_contains($studentName, ',')) {
            [$lastName, $rest] = array_pad(array_map('trim', explode(',', $studentName, 2)), 2, '');
            $parts = preg_split('/\s+/', $rest) ?: [];
        } else {
            $parts = preg_split('/\s+/', $studentName) ?: [];
            $lastName = array_pop($parts) ?? '';
        }

        $firstName = array_shift($parts) ?? '';
        $middleName = count($parts) ? implode(' ', $parts) : null;

        return [
            Str::upper($lastName),
            Str::upper($firstName),
            $middleName ? Str::upper($middleName) : null,
        ];
    }
}
