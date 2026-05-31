<?php

namespace App\Services\Admin\Roles;

use App\Exceptions\ServiceException;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Role\RoleTrait;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService
{
    use RoleTrait;

    /**
     * Get all roles with their associated admins
     *
     * @return Collection
     */
    public function getAllRoles()
    {
        return Role::with(['translations', 'admins' => function ($query) {
            $query->limit(5);
        }])->paginate(9);
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

        $permissionIds = [];
        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('permission', $permissionName)->first();
            if ($permission) {
                $permissionIds[] = $permission->id;
            } else {
                $permission = Permission::create(['permission' => $permissionName]);
                $permissionIds[] = $permission->id;
            }
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
        $data = [
            'permissionsByGroup' => $this->getAdminRoutesGrouped(),
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
