<?php

use App\Support\PermissionHelper;

if (! function_exists('canAccessWithParent')) {
    function canAccessWithParent(mixed $user, string $code, string $guardName = 'web'): bool
    {
        return PermissionHelper::canAccessWithParent($user, $code, $guardName);
    }
}
