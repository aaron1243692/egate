<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SetSchEmployeeController extends Controller
{
    public function index()
    {
        return view('admin.Setup.Schedules.employee');
    }

    public function fetch(Request $request): JsonResponse
    {
        $search = trim((string) $request->get('search', ''));

        $employees = DB::table('egate_data')
            ->leftJoin('schedules', 'schedules.id', '=', 'egate_data.sched')
            ->where('egate_data.role', 2)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('egate_data.id', 'like', "%{$search}%")
                        ->orWhere('egate_data.student_number', 'like', "%{$search}%")
                        ->orWhere('egate_data.name', 'like', "%{$search}%")
                        ->orWhere('schedules.name', 'like', "%{$search}%");
                });
            })
            ->orderBy('egate_data.name')
            ->select([
                'egate_data.id',
                'egate_data.student_number',
                'egate_data.name',
                'egate_data.sched',
                'schedules.name as schedule_name',
            ])
            ->paginate(10);

        return response()->json([
            'employees' => $employees,
            'schedules' => DB::table('schedules')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function updateSchedule(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'schedule_id' => ['nullable', 'integer', 'exists:schedules,id'],
            ]);

            $updated = DB::table('egate_data')
                ->where('id', $id)
                ->where('role', 2)
                ->update([
                    'sched' => $validated['schedule_id'] ?? null,
                    'updated_at' => now(),
                ]);

            if ($updated === 0 && ! DB::table('egate_data')->where('id', $id)->where('role', 2)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Employee schedule updated successfully.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating employee schedule: ' . $e->getMessage(),
            ], 500);
        }
    }
}
