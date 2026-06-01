<?php

namespace App\Services\Admin\Roles;

use App\Exceptions\ServiceException;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Role\RoleTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService
{
    use RoleTrait;

    /**
     * Get all roles with their associated admins
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllRoles(array $filters = [])
    {
        $query = Role::with(['translations', 'admins' => function ($query) {
            $query->limit(5);
        }])->withCount('admins');

        if (! empty($filters)) {
            $query = $query->search($filters);
        }

        return $query->paginate($filters['per_page'] ?? 9);
    }

    /**
     * Get aggregated role statistics.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $now = Carbon::now();

        $totalRoles = Role::query()->count();
        $assignedAdmins = Admin::whereNotNull('role_id')->count();
        $unassignedRoles = Role::query()->doesntHave('admins')->count();

        $avgPermissions = (int) round(
            DB::table('roles')
                ->leftJoin(DB::raw('(SELECT role_id, COUNT(*) as permission_count FROM permission_role GROUP BY role_id) as pr'), 'roles.id', '=', 'pr.role_id')
                ->selectRaw('AVG(COALESCE(pr.permission_count, 0)) as avg_permissions')
                ->first()?->avg_permissions ?? 0
        );

        $mostPopulated = Role::query()
            ->with('translations')
            ->withCount('admins')
            ->orderByDesc('admins_count')
            ->first();
        $createdThisMonth = Role::query()
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        return [
            'totalRoles' => $totalRoles,
            'assignedAdmins' => $assignedAdmins,
            'unassignedRoles' => $unassignedRoles,
            'avgPermissions' => $avgPermissions,
            'mostPopulated' => $mostPopulated,
            'createdThisMonth' => $createdThisMonth,
        ];
    }

    /**
     * Get a role by ID with its permissions
     *
     * @param int $id
     * @return Role
     */
    public function getRoleById(int $id): Role
    {
        return Role::with(['translations', 'permissions'])->findOrFail($id);
    }

    /**
     * Create a new role with permissions
     *
     * @param array $data
     * @return Role
     * @throws Exception
     */
    public function createRole(array $data): Role
    {
        try {
            $role = null;

            DB::transaction(function() use ($data, &$role) {
                $role = Role::create($data['role']);
                $this->syncRolePermissions($role, $data['permissions'] ?? []);
            });

            return $role;
        } catch (Exception $e) {
            Log::error('Failed to create role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing role with permissions
     *
     * @param int $id
     * @param array $data
     * @return Role
     * @throws Exception
     */
    public function updateRole(int $id, array $data): Role
    {
        try {
            $role = $this->getRoleById($id);

            DB::transaction(function() use ($data, $role) {
                $role->update($data['role']);
                $this->syncRolePermissions($role, $data['permissions'] ?? []);
            });

            return $role;
        } catch (Exception $e) {
            Log::error('Failed to update role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a role by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool
    {
        try {
            $role = $this->getRoleById($id);
            return $role->delete();
        } catch (Exception $e) {
            Log::error('Failed to delete role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync role permissions
     *
     * @param Role $role
     * @param array $permissionNames
     * @return void
     */
    private function syncRolePermissions(Role $role, array $permissionNames): void
    {
        $permissionIds = $this->getPermissionIds($permissionNames);
        $role->permissions()->sync($permissionIds);
    }

    /**
     * Get permission IDs from permission names (validates against allowlist)
     *
     * @param array $permissionNames
     * @return array
     * @throws ServiceException
     */
    private function getPermissionIds(array $permissionNames): array
    {
        $validPermissions = self::getValidPermissionNames();

        $invalidPermissions = array_diff($permissionNames, $validPermissions);
        if (!empty($invalidPermissions)) {
            throw ServiceException::validation(
                __('admin/validation.invalid_permission'),
                ['permissions' => array_values($invalidPermissions)]
            );
        }

        $existingPermissions = Permission::whereIn('permission', $permissionNames)
            ->get()
            ->keyBy('permission');

        $permissionIds = [];

        foreach ($permissionNames as $permissionName) {
            $permission = $existingPermissions->get($permissionName);

            if (! $permission) {
                $permission = Permission::create(['permission' => $permissionName]);
                $existingPermissions->put($permissionName, $permission);
            }

            $permissionIds[] = $permission->id;
        }

        return $permissionIds;
    }

    /**
     * Get role permissions as an array of permission names
     *
     * @param Role $role
     * @return array
     */
    public function getRolePermissions(Role $role): array
    {
        return $role->permissions()->pluck('permission')->toArray();
    }

    /**
     * Get data for role form views
     *
     * @param Role|null $role
     * @return array
     */
    public function getFormViewData(?Role $role = null): array
    {
        $permissionsByGroup = $this->getAdminRoutesGrouped();

        $permissionGroupLabels = [];
        foreach (array_keys($permissionsByGroup) as $groupKey) {
            $permissionGroupLabels['admin.' . $groupKey] = self::translateRouteName('admin.' . $groupKey);
        }

        $data = [
            'permissionsByGroup' => $permissionsByGroup,
            'permissionGroupLabels' => $permissionGroupLabels,
        ];

        if ($role) {
            $data['role'] = $role;
            $data['permissions'] = $this->getRolePermissions($role);
        }

        return $data;
    }

    /**
     * Get all valid permission names (from admin routes + existing database permissions)
     *
     * @return array
     */
    public static function getValidPermissionNames(): array
    {
        $routePermissions = collect(self::getAdminRoutesFromFile())->toArray();

        $dbPermissions = Permission::pluck('permission')->toArray();

        return array_unique(array_merge($routePermissions, $dbPermissions));
    }

    /**
     * Get admin route names from file (static version for validation)
     *
     * @return array
     */
    public static function getAdminRoutesFromFile(): array
    {
        $except = ['admin.logout', 'admin.lang.change', 'admin.loginPage', 'admin.login', 'admin.profile', 'admin.roles.getForm'];

        return collect(\Illuminate\Support\Facades\Route::getRoutes('admin'))
            ->filter(fn($route) => $route->getName() !== null)
            ->filter(fn($route) => \Illuminate\Support\Str::startsWith($route->getName(), 'admin.'))
            ->filter(fn($route) => in_array('auth:admin', $route->gatherMiddleware()))
            ->map(fn($route) => $route->getName())
            ->filter(fn(string $fullName) => !in_array($fullName, $except, true))
            ->values()
            ->toArray();
    }
}
