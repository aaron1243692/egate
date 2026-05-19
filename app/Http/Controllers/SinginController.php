<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinginController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        if (! SettingController::isEnabled(1)) {
            return response()->json([
                'message' => 'Manual login is currently disabled.',
                'status' => 0,
            ], 403);
        }

        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $login = trim((string) $validated['login']);
        $password = (string) $validated['password'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $login, 'password' => $password], $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Incorrect credentials',
                'status' => 0,
            ], 422);
        }

        $request->session()->regenerate();

        if (! Auth::user()?->hasRole('admin')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Incorrect credentials',
                'status' => 0,
            ], 403);
        }

        return response()->json([
            'message' => 'Login successful',
            'status' => 1,
            'redirect' => route('admin.dashboard'),
        ]);
    }
}
