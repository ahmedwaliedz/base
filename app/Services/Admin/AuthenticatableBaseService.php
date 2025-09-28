<?php
namespace App\Services\Admin;

class AuthenticatableBaseService extends CrudBaseService {

    public function __construct($model) {
        parent::__construct($model);
    }

}
