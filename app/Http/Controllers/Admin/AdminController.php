<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Services\Admin\AdminService;

class AdminController extends AdminBaseController {

    public function __construct(AdminService $adminService) {
        parent::__construct($adminService);
    }

}
