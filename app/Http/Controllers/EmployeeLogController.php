<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EgateEntryLog;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeLogController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('emlog.view'), 403);

        $departments = DB::table('egate_data')
            ->where('role', 2)
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.employee_logs', compact('departments'));
    }

    public function fetchLogs(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('emlog.view'), 403);

        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));

        $employees = DB::table('egate_data')
            ->where('role', 2)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('id', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%")
                        ->orWhere('lrn', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when($department !== '', function ($query) use ($department) {
                $query->where('department', $department);
            })
            ->select([
                'id',
                'student_number',
                'lrn',
                'rfid',
                'name',
                'email',
                'contact',
                'department',
                'course',
                'grade_level',
            ])
            ->paginate(10);

        return response()->json($employees);
    }

    public function print(Request $request)
    {
        abort_unless(auth()->user()?->can('emlog.print'), 403);

        if ($request->filled('student_id')) {
            return $this->printMonthlyDtr($request);
        }

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
            ])
            ->get()
            ->map(function ($log) {
                $name = trim((string) $log->name);

                return [
                    'student_id' => $log->student_id,
                    'lrn' => $log->lrn,
                    'name' => $name !== '' ? $name : $log->student_id,
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

        return view('admin.print-logs', [
            'logs' => $logs,
            'studentName' => $studentName,
            'studentNumber' => $studentNumber,
            'studentLrn' => $studentLrn,
            'printedAt' => now(),
        ]);
    }

    public function viewEmployeeLogs(Request $request, string $studentId): JsonResponse
    {
        abort_unless(auth()->user()?->can('emlog.view'), 403);

        $employee = $this->findEmployeeForDtr($studentId);
        abort_if(! $employee, 404);

        $year = (int) $request->integer('year', (int) now()->format('Y'));
        $month = (int) $request->integer('month', (int) now()->format('n'));
        $year = $year > 0 ? $year : (int) now()->format('Y');
        $month = $month >= 1 && $month <= 12 ? $month : (int) now()->format('n');
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $logs = DB::table('egate_logs')
            ->where(function ($query) use ($employee) {
                $query
                    ->where('egate_data_id', (int) $employee->id)
                    ->orWhere('student_id', (string) $employee->id)
                    ->when((string) $employee->student_number !== '', function ($innerQuery) use ($employee) {
                        $innerQuery->orWhere('student_id', (string) $employee->student_number);
                    });
            })
            ->whereBetween('created_at', [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')])
            ->orderBy('created_at')
            ->get(['status', 'created_at'])
            ->map(function ($log) {
                return [
                    'status' => $this->resolveStatusLabel((int) $log->status),
                    'time' => $this->formatLogTime($log->created_at),
                ];
            });

        return response()->json([
            'employee' => [
                'id' => $employee->student_number ?: $employee->id,
                'name' => trim((string) $employee->name) ?: ($employee->student_number ?: $employee->id),
            ],
            'period' => $start->format('F Y'),
            'logs' => $logs,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        abort_unless(auth()->user()?->can('emlog.export'), 403);

        $logs = $this->buildFilteredQuery($request)
            ->orderBy('egate_logs.created_at', $this->resolveTimeSortDirection($request))
            ->select([
                'egate_logs.student_id',
                'egate_logs.status',
                'egate_logs.created_at',
                'egate_data.name',
            ])
            ->get()
            ->map(function ($log) {
                $name = trim((string) $log->name);

                return [
                    'student_id' => $log->student_id,
                    'name' => $name !== '' ? $name : $log->student_id,
                    'status' => $this->resolveStatusLabel((int) $log->status),
                    'time' => $log->created_at,
                ];
            });

        $filename = 'employee-logs-' . now()->format('Y-m-d_H-i-s') . '.xls';
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
        abort_unless(auth()->user()?->can('emlog.delete'), 403);

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

    private function printMonthlyDtr(Request $request)
    {
        $employee = $this->findEmployeeForDtr((string) $request->get('student_id'));
        abort_if(! $employee, 404);

        $year = (int) $request->integer('year', (int) now()->format('Y'));
        $month = (int) $request->integer('month', (int) now()->format('n'));
        $year = $year > 0 ? $year : (int) now()->format('Y');
        $month = $month >= 1 && $month <= 12 ? $month : (int) now()->format('n');

        $dtr = $this->buildMonthlyDtr((int) $employee->id, (string) $employee->student_number, $year, $month);

        return view('admin.print-monthly-dtr', [
            'employee' => $employee,
            'monthName' => Carbon::create($year, $month, 1)->format('F Y'),
            'rows' => $dtr['rows'],
            'summary' => $dtr['summary'],
            'printedAt' => now(),
        ]);
    }

    private function findEmployeeForDtr(string $studentId): ?object
    {
        return DB::table('egate_data')
            ->where('role', 2)
            ->where(function ($query) use ($studentId) {
                $query
                    ->where('id', $studentId)
                    ->orWhere('student_number', $studentId);
            })
            ->select(['id', 'student_number', 'name', 'contact', 'email'])
            ->first();
    }

    private function buildMonthlyDtr(int $employeeId, string $studentNumber, int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();
        $daysInMonth = $start->daysInMonth;

        $logsByDay = DB::table('egate_logs')
            ->where(function ($query) use ($employeeId, $studentNumber) {
                $query
                    ->where('egate_data_id', $employeeId)
                    ->orWhere('student_id', (string) $employeeId)
                    ->when($studentNumber !== '', function ($innerQuery) use ($studentNumber) {
                        $innerQuery->orWhere('student_id', $studentNumber);
                    });
            })
            ->whereBetween('created_at', [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')])
            ->orderBy('created_at')
            ->get(['status', 'created_at'])
            ->groupBy(function ($log) {
                return Carbon::parse($log->created_at)->day;
            });

        $rows = [];
        $lateDays = 0;
        $lateMinutes = 0;
        $undertimeDays = 0;
        $undertimeMinutes = 0;
        $absenceDays = 0;
        $totalMinutes = 0;

        for ($day = 1; $day <= 31; $day++) {
            if ($day > $daysInMonth) {
                $rows[] = $this->blankDtrRow();
                continue;
            }

            $date = Carbon::create($year, $month, $day);
            $dayLogs = $logsByDay->get($day, collect());
            $times = $this->resolveDtrDayTimes($dayLogs);
            $lateForDay = $this->calculateLateMinutes($times, $date);
            $undertimeForDay = $this->calculateUndertimeMinutes($times, $date);
            $totalMinutes += $this->calculateWorkedMinutes($times);
            $absent = $date->isWeekday() && $dayLogs->isEmpty();

            if ($lateForDay > 0) {
                $lateDays++;
                $lateMinutes += $lateForDay;
            }

            if ($undertimeForDay > 0) {
                $undertimeDays++;
                $undertimeMinutes += $undertimeForDay;
            }

            if ($absent) {
                $absenceDays++;
            }

            $rows[] = [
                'day' => (string) $day,
                'weekday' => $date->format('D'),
                'am_in' => $times['am_in']?->format('g:i A') ?? '',
                'am_out' => $times['am_out']?->format('g:i A') ?? '',
                'pm_in' => $times['pm_in']?->format('g:i A') ?? '',
                'pm_out' => $times['pm_out']?->format('g:i A') ?? '',
                'late' => $lateForDay > 0 ? $this->formatDurationSummary(0, $lateForDay) : '',
                'undertime' => $undertimeForDay > 0 ? $this->formatDurationSummary(0, $undertimeForDay) : '',
                'absence' => $absent ? $this->formatDurationSummary(1, 0) : '',
            ];
        }

        return [
            'rows' => $rows,
            'summary' => [
                'total_time' => $this->formatDurationSummary(0, $totalMinutes),
                'late' => $this->formatDurationSummary($lateDays, $lateMinutes),
                'undertime' => $this->formatDurationSummary($undertimeDays, $undertimeMinutes),
                'absence' => $this->formatDurationSummary($absenceDays, 0),
            ],
        ];
    }

    private function resolveDtrDayTimes($logs): array
    {
        $times = [
            'am_in' => null,
            'am_out' => null,
            'pm_in' => null,
            'pm_out' => null,
        ];

        foreach ($logs as $log) {
            $time = Carbon::parse($log->created_at);

            if ((int) $log->status === 1 && $time->hour < 12 && $times['am_in'] === null) {
                $times['am_in'] = $time;
            } elseif ((int) $log->status === 0 && $time->hour < 12) {
                $times['am_out'] = $time;
            } elseif ((int) $log->status === 1 && $time->hour >= 12 && $times['pm_in'] === null) {
                $times['pm_in'] = $time;
            } elseif ((int) $log->status === 0 && $time->hour >= 12) {
                $times['pm_out'] = $time;
            }
        }

        return $times;
    }

    private function calculateLateMinutes(array $times, Carbon $date): int
    {
        return $this->minutesAfter($times['am_in'], $date->copy()->setTime(8, 0))
            + $this->minutesAfter($times['pm_in'], $date->copy()->setTime(13, 0));
    }

    private function calculateUndertimeMinutes(array $times, Carbon $date): int
    {
        return $this->minutesBefore($times['am_out'], $date->copy()->setTime(12, 0))
            + $this->minutesBefore($times['pm_out'], $date->copy()->setTime(17, 0));
    }

    private function calculateWorkedMinutes(array $times): int
    {
        return $this->minutesBetween($times['am_in'], $times['am_out'])
            + $this->minutesBetween($times['pm_in'], $times['pm_out']);
    }

    private function minutesBetween(?Carbon $start, ?Carbon $end): int
    {
        return $start && $end && $end->gt($start) ? (int) $start->diffInMinutes($end) : 0;
    }

    private function minutesAfter(?Carbon $actual, Carbon $scheduled): int
    {
        return $actual && $actual->gt($scheduled) ? (int) $scheduled->diffInMinutes($actual) : 0;
    }

    private function minutesBefore(?Carbon $actual, Carbon $scheduled): int
    {
        return $actual && $actual->lt($scheduled) ? (int) $actual->diffInMinutes($scheduled) : 0;
    }

    private function formatDurationSummary(int $days, int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return sprintf('%d d, %d h, %d m', $days, $hours, $remainingMinutes);
    }

    private function blankDtrRow(): array
    {
        return [
            'day' => '',
            'weekday' => '',
            'am_in' => '',
            'am_out' => '',
            'pm_in' => '',
            'pm_out' => '',
            'late' => '',
            'undertime' => '',
            'absence' => '',
        ];
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
        $dateFrom = trim((string) $request->get('date_from', ''));
        $dateTo = trim((string) $request->get('date_to', ''));
        $year = (int) $request->integer('year', 0);
        $month = (int) $request->integer('month', 0);

        if ($year > 0 && $month >= 1 && $month <= 12) {
            $date = Carbon::create($year, $month, 1);
            $dateFrom = $date->copy()->startOfMonth()->format('Y-m-d H:i:s');
            $dateTo = $date->copy()->endOfMonth()->format('Y-m-d H:i:s');
        }

        return DB::table('egate_logs')
            ->leftJoin('egate_data', function ($join) {
                $join
                    ->on('egate_data.id', '=', 'egate_logs.egate_data_id')
                    ->orOn('egate_data.student_number', '=', 'egate_logs.student_id');
            })
            ->where('egate_data.role', 2)
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
