<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $parentId = DB::table('permissions')->where('id', 36)->exists() ? 36 : null;

        $legacyTimeLogin = DB::table('permissions')
            ->where('code', 'time.login')
            ->where('guard_name', 'web')
            ->first();

        $timeOut = DB::table('permissions')
            ->where('code', 'time.out')
            ->where('guard_name', 'web')
            ->first();

        if ($legacyTimeLogin && ! $timeOut) {
            DB::table('permissions')
                ->where('id', $legacyTimeLogin->id)
                ->update([
                    'name' => 'Out',
                    'code' => 'time.out',
                    'parent_id' => $parentId,
                    'updated_at' => now(),
                ]);
        } elseif ($legacyTimeLogin && $timeOut) {
            $roleLinks = DB::table('role_has_permissions')
                ->where('permission_id', $legacyTimeLogin->id)
                ->get()
                ->map(fn ($link) => [
                    'permission_id' => $timeOut->id,
                    'role_id' => $link->role_id,
                ])
                ->all();

            if ($roleLinks) {
                DB::table('role_has_permissions')->insertOrIgnore($roleLinks);
            }

            $modelLinks = DB::table('model_has_permissions')
                ->where('permission_id', $legacyTimeLogin->id)
                ->get()
                ->map(fn ($link) => [
                    'permission_id' => $timeOut->id,
                    'model_type' => $link->model_type,
                    'model_id' => $link->model_id,
                ])
                ->all();

            if ($modelLinks) {
                DB::table('model_has_permissions')->insertOrIgnore($modelLinks);
            }

            DB::table('permissions')->where('id', $legacyTimeLogin->id)->delete();

            DB::table('permissions')
                ->where('id', $timeOut->id)
                ->update([
                    'name' => 'Out',
                    'parent_id' => $parentId,
                    'updated_at' => now(),
                ]);
        } elseif ($timeOut) {
            DB::table('permissions')
                ->where('id', $timeOut->id)
                ->update([
                    'name' => 'Out',
                    'parent_id' => $parentId,
                    'updated_at' => now(),
                ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        DB::table('permissions')
            ->where('code', 'time.out')
            ->where('guard_name', 'web')
            ->where('parent_id', 36)
            ->update([
                'name' => 'Login',
                'code' => 'time.login',
                'updated_at' => now(),
            ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
