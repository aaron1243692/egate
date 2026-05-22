<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CsrfTokenController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        if ($request->boolean('fresh')) {
            $request->session()->regenerateToken();
        }

        return response()->json([
            'token' => csrf_token(),
        ]);
    }
}
