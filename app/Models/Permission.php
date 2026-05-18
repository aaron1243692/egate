<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'code',
        'parent_id',
        'guard_name',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @throws PermissionAlreadyExists
     */
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] ??= Guard::getDefaultName(static::class);
        $attributes['code'] ??= $attributes['name'] ?? null;

        $permission = static::getPermission([
            'code' => $attributes['code'],
            'guard_name' => $attributes['guard_name'],
        ]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['code'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    /**
     * Resolve permissions by code so @can(), middleware, and hasPermissionTo()
     * all use the permission key instead of the UI label.
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByName(string $name, ?string $guardName = null): PermissionContract
    {
        $guardName ??= Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            'code' => $name,
            'guard_name' => $guardName,
        ]);

        if (! $permission) {
            throw PermissionDoesNotExist::create($name, $guardName);
        }

        return $permission;
    }

    public static function findOrCreate(string $name, ?string $guardName = null): PermissionContract
    {
        $guardName ??= Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            'code' => $name,
            'guard_name' => $guardName,
        ]);

        if (! $permission) {
            return static::query()->create([
                'name' => $name,
                'code' => $name,
                'guard_name' => $guardName,
            ]);
        }

        return $permission;
    }
}
