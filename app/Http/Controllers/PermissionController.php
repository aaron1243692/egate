<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions');
    }

    public function fetchPermissions(Request $request): JsonResponse
    {
        $search = trim((string) $request->get('search', ''));

        $permissions = Permission::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->paginate(10);

        return response()->json($permissions);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $name = trim((string) $validated['name']);
            $code = $this->makeCode($name);

            if (Permission::query()->where('code', $code)->where('guard_name', 'web')->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A permission with this name already exists.',
                ], 422);
            }

            $permission = Permission::query()->create([
                'name' => $name,
                'code' => $code,
                'guard_name' => 'web',
                'parent_id' => null,
            ]);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully.',
                'permission' => $permission,
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
                'message' => 'Error creating permission: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $permission = Permission::query()->findOrFail($id);

            return response()->json([
                'success' => true,
                'permission' => $permission,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.',
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $permission = Permission::query()->findOrFail($id);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $name = trim((string) $validated['name']);
            $code = $this->makeCode($name);

            if (Permission::query()
                ->where('code', $code)
                ->where('guard_name', 'web')
                ->where('id', '!=', $permission->id)
                ->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A permission with this name already exists.',
                ], 422);
            }

            $permission->update([
                'name' => $name,
                'code' => $code,
            ]);

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully.',
                'permission' => $permission->fresh(),
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
                'message' => 'Error updating permission: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $permission = Permission::query()->findOrFail($id);

            $permission->delete();

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting permission: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function makeCode(string $name): string
    {
        $code = Str::of($name)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '.')
            ->trim('.');

        return $code->isEmpty() ? 'permission' : (string) $code;
    }
}
