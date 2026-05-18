<?php

namespace App\Support;

use App\Models\Permission;

class PermissionHelper
{
    public static function canAccessWithParent(mixed $user, string $code, string $guardName = 'web'): bool
    {
        if (! is_object($user) || ! method_exists($user, 'can')) {
            return false;
        }

        /** @var Permission|null $permission */
        $permission = Permission::query()
            ->where('code', $code)
            ->where('guard_name', $guardName)
            ->first();

        if (! $permission) {
            return false;
        }

        $parent = $permission->parent;

        while ($parent) {
            if (! $user->can($parent->code)) {
                return false;
            }

            $parent = $parent->parent;
        }

        return $user->can($permission->code);
    }
}
