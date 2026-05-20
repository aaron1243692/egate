<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\EgateEntryLog;

class LogController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('logs.view'), 403);

        $departments = DB::table('egate_data')
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        $courses = DB::table('egate_data')
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->orderBy('course')
            ->pluck('course');

        $yearLevels = DB::table('egate_data')
            ->whereNotNull('year_level')
            ->where('year_level', '!=', '')
            ->distinct()
            ->orderBy('year_level')
            ->pluck('year_level');

        return view('admin.logs', compact('departments', 'courses', 'yearLevels'));
    }

    public function fetchLogs(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('logs.view'), 403);
        $search = trim((string) $request->get('search', ''));
        $status = trim((string) $request->get('status', ''));
        $department = trim((string) $request->get('department', ''));
        $course = trim((string) $request->get('course', ''));
        $yearLevel = trim((string) $request->get('year_level', ''));
        $dateFrom = trim((string) $request->get('date_from', ''));
        $dateTo = trim((string) $request->get('date_to', ''));

        $logs = DB::table('egate_logs')
            ->leftJoin('egate_data', function ($join) {
                $join
                    ->on('egate_data.id', '=', 'egate_logs.egate_data_id')
                    ->orOn('egate_data.student_number', '=', 'egate_logs.student_id');
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('egate_logs.student_id', 'like', "%{$search}%")
                        ->orWhere('egate_data.id', 'like', "%{$search}%")
                        ->orWhere('egate_data.student_number', 'like', "%{$search}%")
                        ->orWhere('egate_data.lrn', 'like', "%{$search}%")
                        ->orWhere('egate_data.first_name', 'like', "%{$search}%")
                        ->orWhere('egate_data.middle_name', 'like', "%{$search}%")
                        ->orWhere('egate_data.last_name', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('egate_logs.status', (int) $status);
            })
            ->when($department !== '', function ($query) use ($department) {
                $query->where('egate_data.department', $department);
            })
            ->when($course !== '', function ($query) use ($course) {
                $query->where('egate_data.course', $course);
            })
            ->when($yearLevel !== '', function ($query) use ($yearLevel) {
                $query->where('egate_data.year_level', $yearLevel);
            })
            ->when($dateFrom !== '', function ($query) use ($dateFrom) {
                $query->whereDate('egate_logs.created_at', '>=', $dateFrom);
            })
            ->when($dateTo !== '', function ($query) use ($dateTo) {
                $query->whereDate('egate_logs.created_at', '<=', $dateTo);
            })
            ->orderByDesc('egate_logs.created_at')
            ->select([
                'egate_logs.id',
                'egate_logs.egate_data_id',
                'egate_logs.student_id',
                'egate_logs.status',
                'egate_logs.created_at',
                'egate_data.first_name',
                'egate_data.middle_name',
                'egate_data.last_name',
            ])
            ->paginate(10)
            ->through(function ($log) {
                $nameParts = array_filter([
                    $log->first_name,
                    $log->middle_name,
                    $log->last_name,
                ]);

                $name = count($nameParts) ? implode(' ', $nameParts) : $log->student_id;

                return [
                    'id' => $log->id,
                    'student_id' => $log->student_id,
                    'name' => $name,
                    'status' => $this->resolveStatusLabel((int) $log->status),
                    'time' => $log->created_at,
                ];
            });

        return response()->json($logs);
    }

    public function edit(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('logs.view'), 403);

        try {
            $log = EgateEntryLog::query()->findOrFail($id);

            return response()->json([
                'success' => true,
                'log' => $log,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Log not found.',
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('logs.view'), 403);

        try {
            $log = EgateEntryLog::query()->findOrFail($id);
            $validated = $request->validate([
                'student_id' => ['required', 'string', 'max:255'],
                'status' => ['required', 'integer', 'in:0,1,2'],
                'created_at' => ['required', 'date'],
            ]);

            $log->student_id = $validated['student_id'];
            $log->status = (int) $validated['status'];
            $log->created_at = $validated['created_at'];
            $log->save();

            return response()->json([
                'success' => true,
                'message' => 'Log updated successfully.',
                'log' => $log->fresh(),
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
                'message' => 'Error updating log: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('logs.view'), 403);

        try {
            $log = EgateEntryLog::query()->findOrFail($id);
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting log: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function resolveStatusLabel(int $status): string
    {
        return match ($status) {
            0 => 'Log Out',
            1 => 'Log In',
            2 => 'N/A',
            default => 'N/A',
        };
    }
}
