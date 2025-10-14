<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AuthenticatableBaseController;
use App\Services\Admin\UserService;

class UserController extends AuthenticatableBaseController {

    public function __construct(UserService $userService) {
        parent::__construct($userService);
    }
}
