<?php

namespace App\Http\Controllers;

use App\Models\EgateLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class EgateDashboardController extends Controller
{
    public function __invoke(): View
    {
        $studentLogs = $this->studentLogs();
        $selectedLog = $studentLogs->first();

        return view('welcome', [
            'studentLogs' => $studentLogs,
            'selectedLog' => $selectedLog,
        ]);
    }

    /**
     * Build the dashboard log payload directly from the database.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function studentLogs(): Collection
    {
        if (! Schema::hasTable('egate_logs')) {
            return collect();
        }

        return EgateLog::query()
            ->orderByDesc('logged_at')
            ->orderByDesc('id')
            ->limit(15)
            ->get()
            ->map(function (EgateLog $log) {
                return [
                    'id' => (string) $log->id,
                    'student_number' => $log->student_number,
                    'last_name' => $log->last_name,
                    'first_name' => $log->first_name,
                    'middle_name' => $log->middle_name,
                    'middle_initial' => $log->middle_name ? strtoupper(substr($log->middle_name, 0, 1)) : '',
                    'sex' => $log->sex ?: 'Not set',
                    'department' => $log->department ?: 'Not set',
                    'course' => $log->course ?: 'Not set',
                    'year_level' => $log->year_level ?: 'Not set',
                    'grade_level' => $log->grade_level ?: 'Not set',
                    'image' => $log->image_url,
                    'timestamp' => optional($log->logged_at)->toIso8601String(),
                    'time_label' => optional($log->logged_at)->format('M d, Y h:i A') ?? 'Unknown time',
                    'relative_time' => optional($log->logged_at)->diffForHumans() ?? 'Unavailable',
                ];
            })
            ->values();
    }
}
