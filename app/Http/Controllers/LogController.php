<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
        return view('admin.logs');
    }

    public function fetchLogs(Request $request): JsonResponse
    {
        $search = trim((string) $request->get('search', ''));

        $logs = DB::table('egate_logs')
            ->leftJoin('egate_data', 'egate_data.student_number', '=', 'egate_logs.student_id')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('egate_logs.student_id', 'like', "%{$search}%")
                        ->orWhere('egate_data.student_number', 'like', "%{$search}%")
                        ->orWhere('egate_data.first_name', 'like', "%{$search}%")
                        ->orWhere('egate_data.middle_name', 'like', "%{$search}%")
                        ->orWhere('egate_data.last_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('egate_logs.created_at')
            ->select([
                'egate_logs.id',
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

    private function resolveStatusLabel(int $status): string
    {
        return match ($status) {
            0 => 'Logout',
            1 => 'Login',
            default => 'N/A',
        };
    }
}
