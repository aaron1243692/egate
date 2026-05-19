<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.setting', [
            'settings' => DB::table('config')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'control' => ['required', 'integer'],
        ]);

        $exists = DB::table('config')->where('id', $id)->exists();

        if (! $exists) {
            return response()->json([
                'message' => 'Setting not found.',
            ], 404);
        }

        DB::table('config')
            ->where('id', $id)
            ->update([
                'name' => trim((string) $validated['name']),
                'control' => (int) $validated['control'],
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Setting saved.',
            'setting' => DB::table('config')->where('id', $id)->first(),
        ]);
    }

    public static function isEnabled(int $id): bool
    {
        $control = DB::table('config')->where('id', $id)->value('control');

        return (int) $control === 1;
    }
}
