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
        dd(Role::selectWithTrans(['id as role_id' ,'name'])->get()->toArray());
        return [
            'roles' => Role::selectWithTrans(['id' ,'name'])->get()->toArray(),
        ];
    }

    public function createVars(): array {
        return [
            'roles'     => Role::selectWithTrans(['id' ,'name'])->get(),
            'countries' => Country::where('is_active', true)->selectWithTrans(['id' ,'name'])->get(),
        ];
    }
}