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
    public function index(){

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

        $gradeLevels = DB::table('egate_data')
            ->whereNotNull('grade_level')
            ->where('grade_level', '!=', '')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        return view('admin.employee_logs', compact('departments', 'courses', 'gradeLevels'));
    }

    public function fetchemployeelog(){
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
        ->where('egate_data.role', 2)
        ->paginate(10);

    $logs->getCollection()->transform(function ($log) {
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

    return $logs;

        return response()->json($logs);
    }


}
