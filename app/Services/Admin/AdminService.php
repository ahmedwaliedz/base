<?php
namespace App\Services\Admin;

use App\Enums\AdminType;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Role;
use Exception;

class AdminService extends CrudBaseService {

    public function __construct() {
        parent::__construct(Admin::class);
    }

    public function indexVars(): array {
        return [
            'roles' => Role::forSelect(['id', 'name'])->toArray(),
        ];
    }

    public function createVars(): array {
        return [
            'roles'                       => Role::forSelect(['id', 'name'])->toArray(),
            'countries'                   => Country::where('is_active', true)->forSelect(['code as id', 'code as name'])->toArray(),
            'types'                       => AdminType::forSelect(),
            'receiveNotificationsOptions' => [
                ['id' => true, 'name' => __('admin/main.yes')],
                ['id' => false, 'name' => __('admin/main.no')],
            ],
        ];
    }

    public function editVars(): array {
        return [
            'roles'                       => Role::forSelect(['id', 'name'])->toArray(),
            'countries'                   => Country::where('is_active', true)->forSelect(['code as id', 'code as name'])->toArray(),
            'types'                       => AdminType::forSelect(),
            'receiveNotificationsOptions' => [
                ['id' => true, 'name' => __('admin/main.yes')],
                ['id' => false, 'name' => __('admin/main.no')],
            ],
        ];
    }

    public function destroy($id, $function = null) {
        return parent::destroy($id, function ($object) {
            if ($object->id == 1) {
                throw new Exception(__('admin/main.you_cannot_delete_the_super_admin'));
            }
        });
    }
}
