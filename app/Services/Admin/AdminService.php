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
}
