<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AuthenticatableBaseController;
use App\Services\Admin\AdminService;

class AdminController extends AuthenticatableBaseController {

    public function __construct(AdminService $adminService) {
        parent::__construct($adminService);
    }

}
