<?php
namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Role;

class AdminService extends AdminBaseService {

    public function __construct() {
        parent::__construct(Admin::class);
    }

    public function indexVars(): array {
        return [
            'roles' => Role::get(),
        ];
    }

    $roles = Role::get()->map(function ($role) {
        return [
            'id'   => $role->id,
            'name' => $role->name,
        ];
    })->toArray();
    $countries = Country::where('is_active', true)->get()->map(function ($country) {
        return [
            'id'   => $country->code,
            'name' => $country->code,
        ];
    })->toArray();

    public function createVars(): array {
        return [
            'roles'     => Role::translations(['name'])->select('id', 'name')->get()->toArray(),
            'countries' => Country::where('is_active', true)->translations(['name'])->select('id', 'name')->get()->toArray(),
        ];
    }
}