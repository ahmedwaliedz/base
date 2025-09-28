<?php
namespace App\Http\Controllers\Admin;

class AuthenticatableBaseController extends AdminBaseController {

    public function __construct($service) {
        parent::__construct($service);
    }

    
}
