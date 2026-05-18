<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class EgateDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('welcome');
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

        $response = Http::withoutVerifying()
            ->acceptJson()
            ->timeout(15)
            ->get('https://app.olpcc.online/api/admin/id-migrations/students', [
                'school_year_id' => 77
            ]);

        $data = collect($response->json('data', []))
            ->take(2)
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
