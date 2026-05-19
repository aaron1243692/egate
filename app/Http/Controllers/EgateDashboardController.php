<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EgateDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('welcome', [
            'manualEntryEnabled' => SettingController::isEnabled(1),
            'rfidLoginEnabled' => SettingController::isEnabled(2),
        ]);
    }

    public function showLogin(): View
    {
        return view('login');
    }

    public function adminDashboard(): View
    {
        return view('welcome');
    }

    public function getStudents(Request $request): JsonResponse
    {
        $data = DB::table('egate_logs')
            ->leftJoin('egate_data', function ($join) {
                $join
                    ->on('egate_data.id', '=', 'egate_logs.egate_data_id')
                    ->orOn('egate_data.student_number', '=', 'egate_logs.student_id');
            })
            ->orderByDesc('egate_logs.created_at')
            ->select([
                'egate_logs.id as log_id',
                'egate_logs.student_id',
                'egate_logs.status as log_status',
                'egate_logs.created_at',
                'egate_data.id',
                'egate_data.student_number',
                'egate_data.lrn',
                'egate_data.first_name',
                'egate_data.middle_name',
                'egate_data.last_name',
                'egate_data.department',
                'egate_data.course',
                'egate_data.year_level',
                'egate_data.grade_level',
                'egate_data.image',
                'egate_data.remarks',
            ])
            ->take(2)
            ->get()
            ->map(function ($student) {
                $name = collect([
                    $student->first_name,
                    $student->middle_name,
                    $student->last_name,
                ])->filter()->implode(' ');

                return [
                    'id' => $student->id,
                    'log_id' => $student->log_id,
                    'student_id' => $student->student_id,
                    'student_number' => $student->student_number,
                    'lrn' => $student->lrn,
                    'student_name' => $name !== '' ? $name : $student->student_id,
                    'department' => $student->department,
                    'course' => $student->course,
                    'year_level' => $student->year_level,
                    'grade_level' => $student->grade_level,
                    'image' => $student->image,
                    'status' => (int) $student->log_status === 1 ? 'IN' : 'OUT',
                    'logged_at' => $student->created_at,
                ];
            })
            ->values();

        return response()->json($data);
    }

    public function submitLogin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $login = trim((string) $validated['login']);
        $password = (string) $validated['password'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $login, 'password' => $password], $request->boolean('remember'))) {
            return back()
                ->withInput($request->except('password'))
                ->with('login_error', 'Incorrect credentials');
        }

        $request->session()->regenerate();

        if (! Auth::user()?->hasRole('admin')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->except('password'))
                ->with('login_error', 'Incorrect credentials');
        }

        return redirect()->route('admin.dashboard');
    }


}
