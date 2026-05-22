<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('users.view'), 403);
        return view('admin.users');
    }

    /**
     * Fetch users with search functionality (API endpoint)
     */
    public function fetchUsers(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('users.view'), 403);
        $search = $request->get('search', '');

        $users = User::query()
            ->with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->paginate(10);

        return response()->json($users);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('users.create'), 403);
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|exists:roles,name',
            ]);

            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $this->syncUserRoleByName($user->id, $validated['role']);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => $user->load('roles')
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single user for editing
     */
    public function edit($id): JsonResponse
    {
        abort_unless(auth()->user()?->can('users.update'), 403);
        try {
            $user = User::with('roles')->findOrFail($id);

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('users.update'), 403);
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'role' => 'required|exists:roles,name',
            ]);

            $user->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
            ]);

            $this->syncUserRoleByName($user->id, $validated['role']);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $user->load('roles')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request, $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('users.update.pass'), 403);
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating password: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified user
     */
    public function destroy($id): JsonResponse
    {
        abort_unless(auth()->user()?->can('users.delete'), 403);
        try {
            $user = User::findOrFail($id);

            // Prevent deleting the last admin
            if ($this->userHasRoleName($user->id, 'admin') && $this->countUsersByRoleName('admin') === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the last admin user.',
                ], 422);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all available roles
     */
    public function getRoles(): JsonResponse
    {
        abort_unless(auth()->user()?->can('users.view'), 403);
        try {
            $roles = Role::query()->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'roles' => $roles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching roles: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function syncUserRoleByName(int $userId, string $roleName): void
    {
        abort_unless(auth()->user()?->can('users.view'), 403);
        $roleId = Role::query()
            ->where('name', $roleName)
            ->value('id');

        if (! $roleId) {
            throw ValidationException::withMessages([
                'role' => ['The selected role is invalid.'],
            ]);
        }

        DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->where('model_id', $userId)
            ->delete();

        DB::table('model_has_roles')->insert([
            'role_id' => $roleId,
            'model_type' => User::class,
            'model_id' => $userId,
        ]);
    }

    private function userHasRoleName(int $userId, string $roleName): bool
    {
        abort_unless(auth()->user()?->can('users.view'), 403);
        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('model_has_roles.model_id', $userId)
            ->whereRaw('LOWER(roles.name) = ?', [strtolower($roleName)])
            ->exists();
    }

    private function countUsersByRoleName(string $roleName): int
    {
        abort_unless(auth()->user()?->can('users.view'), 403);
        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->whereRaw('LOWER(roles.name) = ?', [strtolower($roleName)])
            ->distinct('model_has_roles.model_id')
            ->count('model_has_roles.model_id');
    }
}
