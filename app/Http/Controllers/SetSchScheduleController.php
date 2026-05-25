<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SetSchScheduleController extends Controller
{
    public function index()
    {
        return view('admin.Setup.Schedules.schedule');
    }

    public function fetch(Request $request): JsonResponse
    {
        $search = trim((string) $request->get('search', ''));

        $schedules = DB::table('schedules')
            ->select('id', 'name')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(10);

        return response()->json($schedules);
    }

    public function show(int $id): JsonResponse
    {
        $schedule = DB::table('schedules')
            ->select('id', 'name')
            ->where('id', $id)
            ->first();

        if (! $schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'schedule' => $schedule,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate($this->rules());

            $id = DB::table('schedules')->insertGetId([
                'name' => $this->normalizeName($validated['name']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully.',
                'schedule' => DB::table('schedules')->select('id', 'name')->where('id', $id)->first(),
            ], 201);
        } catch (ValidationException $e) {
            return $this->validationError($e);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating schedule: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate($this->rules($id));

            $updated = DB::table('schedules')
                ->where('id', $id)
                ->update([
                    'name' => $this->normalizeName($validated['name']),
                ]);

            if ($updated === 0 && ! DB::table('schedules')->where('id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully.',
                'schedule' => DB::table('schedules')->select('id', 'name')->where('id', $id)->first(),
            ]);
        } catch (ValidationException $e) {
            return $this->validationError($e);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating schedule: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = DB::transaction(function () use ($id) {
                DB::table('sched_details')->where('schedule_id', $id)->delete();

                return DB::table('schedules')->where('id', $id)->delete();
            });

            if ($deleted === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting schedule: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function details(int $id): JsonResponse
    {
        $schedule = DB::table('schedules')
            ->select('id', 'name')
            ->where('id', $id)
            ->first();

        if (! $schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found.',
            ], 404);
        }

        $savedDetails = DB::table('sched_details')
            ->select('day', 'am_in', 'am_out', 'pm_in', 'pm_out')
            ->where('schedule_id', $id)
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $details = collect($this->days())->map(function (string $name, int $day) use ($savedDetails) {
            $detail = $savedDetails->get($day);

            return [
                'day' => $day,
                'name' => $name,
                'am_in' => $this->formatTime($detail->am_in ?? null),
                'am_out' => $this->formatTime($detail->am_out ?? null),
                'pm_in' => $this->formatTime($detail->pm_in ?? null),
                'pm_out' => $this->formatTime($detail->pm_out ?? null),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'schedule' => $schedule,
            'details' => $details,
        ]);
    }

    public function saveDetails(Request $request, int $id): JsonResponse
    {
        if (! DB::table('schedules')->where('id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found.',
            ], 404);
        }

        try {
            $validated = $request->validate([
                'details' => ['required', 'array', 'size:7'],
                'details.*.day' => ['required', 'integer', 'between:1,7'],
                'details.*.am_in' => ['nullable', 'date_format:H:i'],
                'details.*.am_out' => ['nullable', 'date_format:H:i'],
                'details.*.pm_in' => ['nullable', 'date_format:H:i'],
                'details.*.pm_out' => ['nullable', 'date_format:H:i'],
            ]);

            $details = collect($validated['details'])
                ->map(function (array $detail) {
                    $detail['day'] = (int) $detail['day'];

                    return $detail;
                })
                ->keyBy('day')
                ->sortKeys()
                ->values();

            $submittedDays = $details->pluck('day')->map(fn ($day) => (int) $day)->sort()->values()->all();

            if ($details->count() !== 7 || $submittedDays !== range(1, 7)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule details must include days 1 to 7.',
                ], 422);
            }

            DB::transaction(function () use ($id, $details) {
                DB::table('sched_details')->where('schedule_id', $id)->delete();

                $now = now();
                DB::table('sched_details')->insert($details->map(fn (array $detail) => [
                    'schedule_id' => $id,
                    'day' => $detail['day'],
                    'am_in' => $detail['am_in'] ?: null,
                    'am_out' => $detail['am_out'] ?: null,
                    'pm_in' => $detail['pm_in'] ?: null,
                    'pm_out' => $detail['pm_out'] ?: null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all());
            });

            return response()->json([
                'success' => true,
                'message' => 'Schedule details saved successfully.',
            ]);
        } catch (ValidationException $e) {
            return $this->validationError($e);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving schedule details: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:schedules,name' . ($ignoreId ? ',' . $ignoreId : '')],
        ];
    }

    private function days(): array
    {
        return [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];
    }

    private function formatTime(?string $time): string
    {
        return $time ? substr($time, 0, 5) : '';
    }

    private function normalizeName(string $name): string
    {
        return trim(preg_replace('/\s+/', ' ', $name) ?? $name);
    }

    private function validationError(ValidationException $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    }
}
