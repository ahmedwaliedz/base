<?php

namespace App\Services\Admin\Roles;

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
        return Role::with(['admins' => function ($query) {
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
        return Role::with('permissions')->findOrFail($id);
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
     * Get permission IDs from permission names
     *
     * @param array $permissionNames
     * @return array
     */
    private function getPermissionIds(array $permissionNames): array
    {
        return collect($permissionNames)
            ->map(function (string $permissionName) {
                return Permission::firstOrCreate(
                    ['permission' => $permissionName]
                )->id;
            })->toArray();
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
}
