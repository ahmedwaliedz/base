<?php

namespace App\Services\Admin\Roles;

use App\Models\Permission;
use App\Models\Role;
use App\Traits\Role\RoleTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoleService
{
    use RoleTrait;

    /**
     * Get all roles with their admins
     *
     * @return Collection
     */
    public function getAllRoles(): Collection
    {
        return Role::with('admins')->get();
    }

    /**
     * Get a role by ID
     *
     * @param int $id
     * @return Role
     */
    public function getRoleById(int $id): Role
    {
        return Role::findOrFail($id);
    }

    /**
     * Create a new role with permissions
     *
     * @param array $data
     * @return Role
     */
    public function createRole(array $data): Role
    {
        $role = null;

        DB::transaction(function() use ($data, &$role) {
            $role = Role::create($data['role']);
            $permissionIds = $this->getPermissionIds($data['permissions']);
            $role->permissions()->sync($permissionIds);
        });

        return $role;
    }

    /**
     * Update a role with permissions
     *
     * @param int $id
     * @param array $data
     * @return Role
     */
    public function updateRole(int $id, array $data): Role
    {
        $role = $this->getRoleById($id);

        DB::transaction(function() use ($data, $role) {
            $role->update($data['role']);
            $permissionIds = $this->getPermissionIds($data['permissions']);
            $role->permissions()->sync($permissionIds);
        });

        return $role;
    }

    /**
     * Delete a role
     *
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool
    {
        $role = $this->getRoleById($id);
        return $role->delete();
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
     * Get role permissions
     *
     * @param Role $role
     * @return array
     */
    public function getRolePermissions(Role $role): array
    {
        return $role->permissions()->pluck('permission')->toArray();
    }

    /**
     * Get view data for role form
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
            $permissions = $this->getRolePermissions($role);
            $data['role'] = $role;
            $data['permissions'] = $permissions;
        }

        return $data;
    }
}
