<?php

namespace Database\Seeders;

use App\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::findOrCreate('admin', 'web');
        $staff = Role::findOrCreate('Staff', 'web');
        $guard = Role::findOrCreate('Guard', 'web');

        $parents = collect([
            'data' => 'Data',
            'logs' => 'Logs',
            'roles' => 'Roles',
            'setschedcehed' => 'Setup Sched Schedule',
            'setschedem' => 'Setup Sched Employee',
            'time' => 'Time Log',
            'users' => 'Users',
        ])->mapWithKeys(function (string $name, string $code) {
            $permission = Permission::query()->updateOrCreate(
                ['code' => $code, 'guard_name' => 'web'],
                ['name' => $name, 'parent_id' => null]
            );

            return [$code => $permission];
        });

        $legacyTimeLogin = Permission::query()
            ->where('code', 'time.login')
            ->where('guard_name', 'web')
            ->first();

        $timeOut = Permission::query()
            ->where('code', 'time.out')
            ->where('guard_name', 'web')
            ->first();

        if ($legacyTimeLogin && ! $timeOut) {
            $legacyTimeLogin->update([
                'name' => 'Out',
                'code' => 'time.out',
                'parent_id' => $parents['time']->id,
            ]);
        } elseif ($legacyTimeLogin && $timeOut) {
            $timeOut->roles()->syncWithoutDetaching($legacyTimeLogin->roles()->pluck('id')->all());
            $legacyTimeLogin->delete();
        }

        $definitions = [
            ['name' => 'View Data', 'code' => 'data.view', 'parent_id' => $parents['data']->id],
            ['name' => 'Create Data', 'code' => 'data.create', 'parent_id' => $parents['data']->id],
            ['name' => 'Update Data', 'code' => 'data.update', 'parent_id' => $parents['data']->id],
            ['name' => 'Delete Data', 'code' => 'data.delete', 'parent_id' => $parents['data']->id],
            ['name' => 'Print Data', 'code' => 'data.print', 'parent_id' => $parents['data']->id],
            ['name' => 'Export Data', 'code' => 'data.export', 'parent_id' => $parents['data']->id],
            ['name' => 'View Logs', 'code' => 'logs.view', 'parent_id' => $parents['logs']->id],
            ['name' => 'Update Logs', 'code' => 'logs.update', 'parent_id' => $parents['logs']->id],
            ['name' => 'Delete Logs', 'code' => 'logs.delete', 'parent_id' => $parents['logs']->id],
            ['name' => 'Print Logs', 'code' => 'logs.print', 'parent_id' => $parents['logs']->id],
            ['name' => 'Export Logs', 'code' => 'export.logs', 'parent_id' => $parents['logs']->id],
            ['name' => 'View Roles', 'code' => 'roles.view', 'parent_id' => $parents['roles']->id],
            ['name' => 'Create Roles', 'code' => 'roles.create', 'parent_id' => $parents['roles']->id],
            ['name' => 'Update Roles', 'code' => 'roles.update', 'parent_id' => $parents['roles']->id],
            ['name' => 'Delete Roles', 'code' => 'roles.delete', 'parent_id' => $parents['roles']->id],
            ['name' => 'View', 'code' => 'setschedcehed.view', 'parent_id' => $parents['setschedcehed']->id],
            ['name' => 'Create', 'code' => 'setschedcehed.create', 'parent_id' => $parents['setschedcehed']->id],
            ['name' => 'Update', 'code' => 'setschedcehed.update', 'parent_id' => $parents['setschedcehed']->id],
            ['name' => 'Delete', 'code' => 'setschedcehed.delete', 'parent_id' => $parents['setschedcehed']->id],
            ['name' => 'View', 'code' => 'setschedem.view', 'parent_id' => $parents['setschedem']->id],
            ['name' => 'Update', 'code' => 'setschedem.update', 'parent_id' => $parents['setschedem']->id],
            ['name' => 'In', 'code' => 'time.in', 'parent_id' => $parents['time']->id],
            ['name' => 'Out', 'code' => 'time.out', 'parent_id' => $parents['time']->id],
            ['name' => 'None', 'code' => 'time.none', 'parent_id' => $parents['time']->id],
            ['name' => 'View Users', 'code' => 'users.view', 'parent_id' => $parents['users']->id],
            ['name' => 'Create Users', 'code' => 'users.create', 'parent_id' => $parents['users']->id],
            ['name' => 'Update Users', 'code' => 'users.update', 'parent_id' => $parents['users']->id],
            ['name' => 'Update User Passwords', 'code' => 'users.update.pass', 'parent_id' => $parents['users']->id],
            ['name' => 'Delete Users', 'code' => 'users.delete', 'parent_id' => $parents['users']->id],
        ];

        foreach ($definitions as $definition) {
            Permission::query()->updateOrCreate(
                ['code' => $definition['code'], 'guard_name' => 'web'],
                ['name' => $definition['name'], 'parent_id' => $definition['parent_id']]
            );
        }

        $allPermissions = Permission::query()
            ->where('guard_name', 'web')
            ->pluck('id')
            ->all();

        $staffPermissions = Permission::query()
            ->whereIn('code', [
                'data',
                'data.create',
                'data.export',
                'data.print',
                'data.update',
                'data.view',
                'logs',
                'export.logs',
                'logs.print',
                'logs.update',
                'logs.view',
                'time',
                'time.in',
                'time.none',
                'time.out',
                'users',
                'users.view',
                'users.create',
                'users.update',
            ])
            ->where('guard_name', 'web')
            ->get();

        $guardPermissions = Permission::query()
            ->whereIn('code', [
                'data',
                'data.export',
                'data.print',
                'data.view',
                'logs',
                'export.logs',
                'logs.view',
                'logs.print',
                'time',
                'time.in',
                'time.none',
                'time.out',
            ])
            ->where('guard_name', 'web')
            ->get();

        $admin->syncPermissions($allPermissions);
        $staff->syncPermissions($staffPermissions);
        $guard->syncPermissions($guardPermissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
