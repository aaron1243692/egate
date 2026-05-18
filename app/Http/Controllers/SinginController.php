<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;

class SinginController extends Controller
{
    public function submit(Request $request)
    {
        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 0,
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password',
                'status' => 0,
            ]);
        }

        return response()->json([
            'message' => 'Login successful',
            'status' => 1,
        ]);
    }
}
