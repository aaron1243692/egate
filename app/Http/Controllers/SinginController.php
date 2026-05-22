<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinginController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $login = trim((string) $validated['login']);
        $password = (string) $validated['password'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $login, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Incorrect credentials',
                'status' => 0,
                'csrf_token' => csrf_token(),
            ], 422)->header('X-CSRF-TOKEN', csrf_token());
        }

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        if (! Auth::user()?->hasAdminRole()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Incorrect credentials',
                'status' => 0,
                'csrf_token' => csrf_token(),
            ], 403)->header('X-CSRF-TOKEN', csrf_token());
        }

        return response()->json([
            'message' => 'Login successful',
            'status' => 1,
            'redirect' => route('admin.dashboard'),
            'csrf_token' => csrf_token(),
        ])->header('X-CSRF-TOKEN', csrf_token());
    }
}
