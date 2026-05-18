<?php

namespace Database\Seeders;

use App\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::findOrCreate('admin', 'web');
        $staff = Role::findOrCreate('staff', 'web');
        $guard = Role::findOrCreate('guard', 'web');

        $usersParent = Permission::query()->updateOrCreate(
            ['code' => 'users', 'guard_name' => 'web'],
            ['name' => 'Users', 'parent_id' => null]
        );

        $reportsParent = Permission::query()->updateOrCreate(
            ['code' => 'reports', 'guard_name' => 'web'],
            ['name' => 'Reports', 'parent_id' => null]
        );

        $definitions = [
            ['name' => 'View Users', 'code' => 'users.view', 'parent_id' => $usersParent->id],
            ['name' => 'Create Users', 'code' => 'users.create', 'parent_id' => $usersParent->id],
            ['name' => 'Update Users', 'code' => 'users.update', 'parent_id' => $usersParent->id],
            ['name' => 'Delete Users', 'code' => 'users.delete', 'parent_id' => $usersParent->id],
            ['name' => 'View Reports', 'code' => 'reports.view', 'parent_id' => $reportsParent->id],
            ['name' => 'Export Reports', 'code' => 'reports.export', 'parent_id' => $reportsParent->id],
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
            ->whereIn('code', ['users', 'users.view', 'users.create', 'users.update', 'reports', 'reports.view'])
            ->where('guard_name', 'web')
            ->get();

        $guardPermissions = Permission::query()
            ->whereIn('code', ['users', 'users.view'])
            ->where('guard_name', 'web')
            ->get();

        $admin->syncPermissions($allPermissions);
        $staff->syncPermissions($staffPermissions);
        $guard->syncPermissions($guardPermissions);
    }
}
