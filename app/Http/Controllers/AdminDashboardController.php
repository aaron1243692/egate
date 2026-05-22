<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $filters = [
            'status' => trim((string) $request->get('status', '')),
            'department' => trim((string) $request->get('department', '')),
            'course' => trim((string) $request->get('course', '')),
            'year_level' => trim((string) $request->get('year_level', '')),
            'date_from' => trim((string) $request->get('date_from', '')),
            'date_to' => trim((string) $request->get('date_to', '')),
        ];

        $logsQuery = $this->buildLogsQuery($filters);
        $dataQuery = $this->buildDataQuery($filters);

        $logSummary = [
            'total' => (clone $logsQuery)->count(),
            'log_in' => (clone $logsQuery)->where('egate_logs.status', 1)->count(),
            'log_out' => (clone $logsQuery)->where('egate_logs.status', 0)->count(),
            'na' => (clone $logsQuery)->where('egate_logs.status', 2)->count(),
            'unique_students' => (clone $logsQuery)->distinct()->count('egate_logs.student_id'),
        ];

        $dataSummary = [
            'total' => (clone $dataQuery)->count(),
            'departments' => (clone $dataQuery)->whereNotNull('department')->where('department', '!=', '')->distinct()->count('department'),
            'courses' => (clone $dataQuery)->whereNotNull('course')->where('course', '!=', '')->distinct()->count('course'),
            'year_levels' => (clone $dataQuery)->whereNotNull('grade_level')->where('grade_level', '!=', '')->distinct()->count('grade_level'),
        ];

        $departmentBreakdown = (clone $dataQuery)
            ->select(DB::raw("COALESCE(NULLIF(department, ''), 'N/A') as label"), DB::raw('COUNT(*) as total'))
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'filters' => $filters,
            'departments' => $this->filterOptions('department'),
            'courses' => $this->filterOptions('course'),
            'yearLevels' => $this->filterOptions('grade_level'),
            'logSummary' => $logSummary,
            'dataSummary' => $dataSummary,
            'departmentBreakdown' => $departmentBreakdown,
        ]);
    }

    private function buildLogsQuery(array $filters): Builder
    {
        return DB::table('egate_logs')
            ->leftJoin('egate_data', function ($join) {
                $join
                    ->on('egate_data.id', '=', 'egate_logs.egate_data_id')
                    ->orOn('egate_data.student_number', '=', 'egate_logs.student_id');
            })
            ->when($filters['status'] !== '', fn ($query) => $query->where('egate_logs.status', (int) $filters['status']))
            ->when($filters['department'] !== '', fn ($query) => $query->where('egate_data.department', $filters['department']))
            ->when($filters['course'] !== '', fn ($query) => $query->where('egate_data.course', $filters['course']))
            ->when($filters['year_level'] !== '', fn ($query) => $query->where('egate_data.grade_level', $filters['year_level']))
            ->when($filters['date_from'] !== '', fn ($query) => $query->whereDate('egate_logs.created_at', '>=', $filters['date_from']))
            ->when($filters['date_to'] !== '', fn ($query) => $query->whereDate('egate_logs.created_at', '<=', $filters['date_to']));
    }

    private function buildDataQuery(array $filters): Builder
    {
        return DB::table('egate_data')
            ->when($filters['department'] !== '', fn ($query) => $query->where('department', $filters['department']))
            ->when($filters['course'] !== '', fn ($query) => $query->where('course', $filters['course']))
            ->when($filters['year_level'] !== '', fn ($query) => $query->where('grade_level', $filters['year_level']))
            ->when($filters['date_from'] !== '', fn ($query) => $query->whereDate('created_at', '>=', $filters['date_from']))
            ->when($filters['date_to'] !== '', fn ($query) => $query->whereDate('created_at', '<=', $filters['date_to']));
    }

    private function filterOptions(string $column)
    {
        return DB::table('egate_data')
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }

}
