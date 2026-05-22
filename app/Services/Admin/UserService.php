<?php
namespace App\Services\Admin;

use App\Models\Country;
use App\Models\User;
use App\Services\Admin\Base\AuthenticatableBaseService;

class UserService extends AuthenticatableBaseService {

    public function __construct() {
        parent::__construct(User::class);
    }

    public function createVars(): array {
        return [
            'countries'                   => Country::where('is_active', true)->forSelect(['code as id', 'code as name'])->toArray(),
            'receiveNotificationsOptions' => [
                ['id' => true, 'name' => __('admin/main.yes')],
                ['id' => false, 'name' => __('admin/main.no')],
            ],
        ];
    }

    public function editVars($id = null): array {
        return $this->createVars();
    }

}
