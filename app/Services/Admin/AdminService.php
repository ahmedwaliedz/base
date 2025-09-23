<?php
namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Country;
use App\Models\Role;

class AdminService extends AdminBaseService {

    public function __construct() {
        parent::__construct(Admin::class);
    }

    public function indexVars(): array {
        return [
            'roles' => Role::getForSelect(['id' ,'name'])->toArray(),
        ];
    }

    public function createVars(): array {
        return [
            'roles'     => Role::getForSelect(['id' ,'name'])->toArray(),
            'countries' => Country::where('is_active', true)->getForSelect(['id as country_id' ,'name'])->toArray(),
        ];
    }
}