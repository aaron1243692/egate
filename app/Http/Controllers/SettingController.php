<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const DEFINITIONS = [
        1 => 'Manual Login',
        2 => 'RFID Login',
    ];

    public function index(): View
    {
        return view('admin.settings', [
            'settings' => self::all(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        abort_unless(array_key_exists($id, self::DEFINITIONS), 404);

        self::persist($id, $request->boolean('control'));

        return redirect()
            ->route('admin.settings')
            ->with('status', self::DEFINITIONS[$id] . ' updated successfully.');
    }

    public static function isEnabled(int $id): bool
    {
        self::ensureDefaults();

        return (int) DB::table('config')
            ->where('id', $id)
            ->value('control') === 1;
    }

    public static function all(): array
    {
        self::ensureDefaults();

        return collect(self::DEFINITIONS)
            ->map(function (string $name, int $id): array {
                return [
                    'id' => $id,
                    'name' => $name,
                    'enabled' => self::isEnabled($id),
                ];
            })
            ->values()
            ->all();
    }

    private static function ensureDefaults(): void
    {
        $now = now();

        foreach (self::DEFINITIONS as $id => $name) {
            if (! DB::table('config')->where('id', $id)->exists()) {
                DB::table('config')->insert([
                    'id' => $id,
                    'name' => $name,
                    'control' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                continue;
            }

            DB::table('config')
                ->where('id', $id)
                ->update([
                    'name' => $name,
                    'updated_at' => $now,
                ]);
        }
    }

    private static function persist(int $id, bool $enabled): void
    {
        $now = now();

        DB::table('config')->updateOrInsert(
            ['id' => $id],
            [
                'name' => self::DEFINITIONS[$id],
                'control' => $enabled ? 1 : 0,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }
}
