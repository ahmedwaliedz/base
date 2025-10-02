<?php
namespace App\Services\Admin\Base;

class AuthenticatableBaseService extends CrudBaseService {

    public function __construct($model) {
        parent::__construct($model);
    }

}
