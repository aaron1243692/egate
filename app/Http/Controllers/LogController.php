<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EgateEntryLog;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('logs.view'), 403);

        $departments = DB::table('egate_data')
            ->where('role', 1)
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        $courses = DB::table('egate_data')
            ->where('role', 1)
            ->whereNotNull('course')
            ->where('course', '!=', '')
            ->distinct()
            ->orderBy('course')
            ->pluck('course');

        $gradeLevels = DB::table('egate_data')
            ->where('role', 1)
            ->whereNotNull('grade_level')
            ->where('grade_level', '!=', '')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        return view('admin.logs', compact('departments', 'courses', 'gradeLevels'));
    }

    public function fetchLogs(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('logs.view'), 403);
        $logs = $this->buildFilteredQuery($request)
            ->orderBy('egate_logs.created_at', $this->resolveTimeSortDirection($request))
            ->select([
                'egate_logs.id',
                'egate_logs.egate_data_id',
                'egate_logs.student_id',
                'egate_logs.status',
                'egate_logs.created_at',
                'egate_data.name',
            ])
            ->paginate(10)
            ->through(function ($log) {
                $name = trim((string) $log->name);

                $name = $name !== '' ? $name : $log->student_id;

                return [
                    'id' => $log->id,
                    'student_id' => $log->student_id,
                    'name' => $name,
                    'status' => $this->resolveStatusLabel((int) $log->status),
                    'time' => $this->formatLogTime($log->created_at),
                ];
            });

        return response()->json($logs);
    }

    public function print(Request $request)
    {
        abort_unless(auth()->user()?->can('logs.print'), 403);

        $logs = $this->buildFilteredQuery($request)
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->where('egate_logs.student_id', $request->get('student_id'));
            })
            ->orderBy('egate_logs.created_at', $this->resolveTimeSortDirection($request))
            ->select([
                'egate_logs.student_id',
                'egate_logs.status',
                'egate_logs.created_at',
                'egate_data.name',
                'egate_data.lrn',
                'egate_data.contact',
                'egate_data.email',
            ])
            ->get()
            ->map(function ($log) {
                $name = trim((string) $log->name);

                return [
                    'student_id' => $log->student_id,
                    'lrn' => $log->lrn,
                    'name' => $name !== '' ? $name : $log->student_id,
                    'contact' => $log->contact,
                    'email' => $log->email,
                    'status' => $this->resolveStatusLabel((int) $log->status),
                    'time' => $this->formatLogTime($log->created_at),
                ];
            });

        $studentName = $request->filled('student_id')
            ? ($logs->first()['name'] ?? (string) $request->get('student_id'))
            : null;
        $studentNumber = $request->filled('student_id')
            ? ($logs->first()['student_id'] ?? (string) $request->get('student_id'))
            : null;
        $studentLrn = $request->filled('student_id')
            ? ($logs->first()['lrn'] ?? null)
            : null;
        $studentContact = $request->filled('student_id')
            ? ($logs->first()['contact'] ?? null)
            : null;
        $studentEmail = $request->filled('student_id')
            ? ($logs->first()['email'] ?? null)
            : null;

        return view('admin.print-logs', [
            'logs' => $logs,
            'studentName' => $studentName,
            'studentNumber' => $studentNumber,
            'studentLrn' => $studentLrn,
            'studentContact' => $studentContact,
            'studentEmail' => $studentEmail,
            'printedAt' => now(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        abort_unless(auth()->user()?->can('export.logs'), 403);

        $logs = $this->buildFilteredQuery($request)
            ->orderBy('egate_logs.created_at', $this->resolveTimeSortDirection($request))
            ->select([
                'egate_logs.student_id',
                'egate_logs.status',
                'egate_logs.created_at',
                'egate_data.name',
                'egate_data.contact',
                'egate_data.email',
            ])
            ->get()
            ->map(function ($log) {
                $name = trim((string) $log->name);

                return [
                    'student_id' => $log->student_id,
                    'name' => $name !== '' ? $name : $log->student_id,
                    'contact' => $log->contact,
                    'email' => $log->email,
                    'status' => $this->resolveStatusLabel((int) $log->status),
                    'time' => $log->created_at,
                ];
            });

        $filename = 'logs-' . now()->format('Y-m-d_H-i-s') . '.xls';
        $html = view('admin.export-logs', [
            'logs' => $logs,
        ])->render();

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('logs.delete'), 403);

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
            0 => 'Time Out',
            1 => 'Time In',
            default => 'N/A',
        };
    }

    private function formatLogTime(mixed $value): string
    {
        if (blank($value)) {
            return 'N/A';
        }

        try {
            return Carbon::parse($value)->format('M j, Y g:i A');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    private function resolveTimeSortDirection(Request $request): string
    {
        return $request->get('time_sort') === 'asc' ? 'asc' : 'desc';
    }

    private function buildFilteredQuery(Request $request): Builder
    {
        $search = trim((string) $request->get('search', ''));
        $status = trim((string) $request->get('status', ''));
        $department = trim((string) $request->get('department', ''));
        $course = trim((string) $request->get('course', ''));
        $gradeLevel = trim((string) $request->get('grade_level', ''));
        $dateFrom = trim((string) $request->get('date_from', ''));
        $dateTo = trim((string) $request->get('date_to', ''));

        return DB::table('egate_logs')
            ->leftJoin('egate_data', function ($join) {
                $join
                    ->on('egate_data.id', '=', 'egate_logs.egate_data_id')
                    ->orOn('egate_data.student_number', '=', 'egate_logs.student_id');
            })
            ->where('egate_data.role', 1)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('egate_logs.student_id', 'like', "%{$search}%")
                        ->orWhere('egate_data.id', 'like', "%{$search}%")
                        ->orWhere('egate_data.student_number', 'like', "%{$search}%")
                        ->orWhere('egate_data.lrn', 'like', "%{$search}%")
                        ->orWhere('egate_data.name', 'like', "%{$search}%");
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
            ->when($gradeLevel !== '', function ($query) use ($gradeLevel) {
                $query->where('egate_data.grade_level', $gradeLevel);
            })
            ->when($dateFrom !== '', function ($query) use ($dateFrom) {
                $query->where('egate_logs.created_at', '>=', $this->normalizeDateTimeFilter($dateFrom, false));
            })
            ->when($dateTo !== '', function ($query) use ($dateTo) {
                $query->where('egate_logs.created_at', '<=', $this->normalizeDateTimeFilter($dateTo, true));
            });
    }

    private function normalizeDateTimeFilter(string $value, bool $endOfDay): string
    {
        try {
            $date = Carbon::parse($value);

            if (! str_contains($value, 'T') && ! str_contains($value, ':')) {
                $date = $endOfDay ? $date->endOfDay() : $date->startOfDay();
            }

            return $date->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return str_replace('T', ' ', $value);
        }
    }
}
