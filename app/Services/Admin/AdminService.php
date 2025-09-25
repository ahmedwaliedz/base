<?php
namespace App\Services\Admin;

use App\Enums\AdminType;
use App\Models\Admin;
use App\Models\Country;
use App\Models\Role;

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
                ['id' => 1, 'name' => __('admin/main.yes')],
                ['id' => 0, 'name' => __('admin/main.no')],
            ],
        ];
    }

    public function editVars(): array {
        return [
            'roles'                       => Role::forSelect(['id', 'name'])->toArray(),
            'countries'                   => Country::where('is_active', true)->forSelect(['code as id', 'code as name'])->toArray(),
            'types'                       => AdminType::forSelect(),
            'receiveNotificationsOptions' => [
                ['id' => 1, 'name' => __('admin/main.yes')],
                ['id' => 0, 'name' => __('admin/main.no')],
            ],
        ];
    }
}
