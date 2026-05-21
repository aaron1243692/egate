<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EgateLogoutController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.out', [
            'manualEntryEnabled' => SettingController::isEnabled(1),
            'rfidLoginEnabled' => SettingController::isEnabled(2),
        ]);
    }

    public function showLogin(): View
    {
        return view('signin');
    }

    public function adminDashboard(): View
    {
        return view('pages.out');
    }

    public function getStudents(Request $request): JsonResponse
    {
        return response()->json(app(EgateDashboardController::class)->buildStudentPayload('0'));
    }
}
