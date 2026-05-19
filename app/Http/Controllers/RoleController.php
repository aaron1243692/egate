<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('roles.view'), 403);
        return view('admin.roles');
    }

    public function fetchRoles(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('roles.view'), 403);
        $search = trim((string) $request->get('search', ''));

        $roles = DB::table('roles')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(10);

        return response()->json($roles);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('roles.create'), 403);
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $name = trim((string) $validated['name']);

            if (DB::table('roles')->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A role with this name already exists.',
                ], 422);
            }

            $id = DB::table('roles')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully.',
                'role' => DB::table('roles')->where('id', $id)->first(),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating role: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('roles.update'), 403);
        $role = DB::table('roles')->where('id', $id)->first();

        if (! $role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'role' => $role,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('roles.update'), 403);
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $role = DB::table('roles')->where('id', $id)->first();

            if (! $role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.',
                ], 404);
            }

            $name = trim((string) $validated['name']);

            if (DB::table('roles')
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->where('id', '!=', $id)
                ->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A role with this name already exists.',
                ], 422);
            }

            DB::table('roles')
                ->where('id', $id)
                ->update([
                    'name' => $name,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully.',
                'role' => DB::table('roles')->where('id', $id)->first(),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating role: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('roles.delete'), 403);
        try {
            $role = DB::table('roles')->where('id', $id)->first();

            if (! $role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.',
                ], 404);
            }

            DB::transaction(function () use ($id) {
                DB::table('role_has_permissions')->where('role_id', $id)->delete();
                DB::table('model_has_roles')->where('role_id', $id)->delete();
                DB::table('roles')->where('id', $id)->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting role: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getPermissionTree(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('roles.update'), 403);
        $role = DB::table('roles')->where('id', $id)->first();

        if (! $role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found.',
            ], 404);
        }

        $assignedIds = DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->pluck('permission_id')
            ->all();

        $parents = Permission::query()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'name']);

        $children = Permission::query()
            ->whereNotNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id'])
            ->groupBy('parent_id');

        $tree = $parents->map(function ($parent) use ($children, $assignedIds) {
            return [
                'id' => $parent->id,
                'name' => $parent->name,
                'checked' => in_array($parent->id, $assignedIds, true),
                'children' => collect($children->get($parent->id, []))
                    ->map(fn ($child) => [
                        'id' => $child->id,
                        'name' => $child->name,
                        'checked' => in_array($child->id, $assignedIds, true),
                    ])
                    ->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'role' => $role,
            'permissions' => $tree,
        ]);
    }

    public function updatePermissions(Request $request, int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('roles.update'), 403);
        try {
            $role = DB::table('roles')->where('id', $id)->first();

            if (! $role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.',
                ], 404);
            }

            $validated = $request->validate([
                'permission_ids' => ['nullable', 'array'],
                'permission_ids.*' => ['integer', 'exists:permissions,id'],
            ]);

            $permissionIds = collect($validated['permission_ids'] ?? [])
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values();

            DB::transaction(function () use ($id, $permissionIds) {
                DB::table('role_has_permissions')->where('role_id', $id)->delete();

                if ($permissionIds->isNotEmpty()) {
                    DB::table('role_has_permissions')->insert(
                        $permissionIds->map(fn ($permissionId) => [
                            'role_id' => $id,
                            'permission_id' => $permissionId,
                        ])->all()
                    );
                }
            });

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return response()->json([
                'success' => true,
                'message' => 'Role permissions updated successfully.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating role permissions: ' . $e->getMessage(),
            ], 500);
        }
    }
}
