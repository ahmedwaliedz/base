<?php

namespace App\Services\Admin;

use App\Enums\AdminType;
use App\Exceptions\ServiceException;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Role;
use App\Services\Admin\Base\AuthenticatableBaseService;
use App\Traits\Role\RoleTrait;

class AdminService extends AuthenticatableBaseService
{
    use RoleTrait;

    public function __construct()
    {
        parent::__construct(Admin::class);
    }

    /**
     * Load admin with role permissions and attach grouped permission metadata for the show page.
     *
     * @param  int|string  $id
     * @return array<string, mixed>
     */
    public function index($request, $where = [])
    {
        return parent::index($request, $where)->with(['role.translations']);
    }

    public function show($id): array
    {
        $query = $this->model::query()->with(['role.translations', 'role.permissions']);

        if ($this->getIsRetrievable()) {
            $query = $query->withTrashed();
        }

        /** @var Admin $admin */
        $admin = $query->findOrFail($id);

        $permissions = $admin->role
            ? $admin->role->permissions->pluck('permission')->toArray()
            : [];

        $permissionsByGroup = self::getAdminRoutesGrouped();
        $permissionGroupLabels = [];
        foreach (array_keys($permissionsByGroup) as $groupKey) {
            $permissionGroupLabels['admin.' . $groupKey] = self::translateRouteName('admin.' . $groupKey);
        }
        $permissionsCount = count($permissions);

        return array_merge($this->showVars(), [
            $this->lowerClassName => $admin,
            'id' => $id,
            'lowerClassName' => $this->lowerClassName,
            'permissions' => $permissions,
            'permissionsByGroup' => $permissionsByGroup,
            'permissionGroupLabels' => $permissionGroupLabels,
            'permissionsCount' => $permissionsCount,
        ]);
    }

public function indexVars(): array
    {
        return [
            'roles' => Role::with('translations')->forSelect(['id', 'name'])->toArray(),
        ];
    }

    public function createVars(): array
    {
        return [
            'roles' => Role::with('translations')->forSelect(['id', 'name'])->toArray(),
            'countries' => Country::where('is_active', true)->forSelect(['code as id', 'code as name'])->toArray(),
            'types' => AdminType::forSelect(),
            'receiveNotificationsOptions' => [
                ['id' => true, 'name' => __('admin/main.yes')],
                ['id' => false, 'name' => __('admin/main.no')],
            ],
        ];
    }

    public function editVars($id = null): array
    {
        return [
            'roles' => Role::with('translations')->forSelect(['id', 'name'])->toArray(),
            'countries' => Country::where('is_active', true)->with('translations')->forSelect(['code as id', 'code as name'])->toArray(),
            'types' => AdminType::forSelect(),
            'receiveNotificationsOptions' => [
                ['id' => true, 'name' => __('admin/main.yes')],
                ['id' => false, 'name' => __('admin/main.no')],
            ],
        ];
    }

    /**
     * Get active country options as [id => code, name => code]
     * for profile and form select dropdowns.
     */
    public function getActiveCountryCodeOptions(): array
    {
        return Country::where('is_active', true)
            ->get()
            ->map(fn ($country) => [
                'id' => $country->code,
                'name' => $country->code,
            ])
            ->toArray();
    }

    public function destroy($id, $function = null)
    {
        return parent::destroy($id, function ($object) {
            if ($object->id == 1) {
                throw ServiceException::forModel(
                    $this->model,
                    'destroy',
                    __('admin/main.you_cannot_delete_the_super_admin'),
                    403,
                    ['id' => $object->id]
                );
            }
        });
    }

    public function destroyAll($ids, $function = null)
    {
        if (in_array(1, array_map('intval', $ids), true)) {
            throw ServiceException::forModel(
                $this->model,
                'destroyAll',
                __('admin/main.you_cannot_delete_the_super_admin'),
                403,
                ['ids' => $ids]
            );
        }

        return parent::destroyAll($ids, $function);
    }
}
