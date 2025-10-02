<?php
namespace App\Http\Controllers\Admin;

use Exception;

class AuthenticatableBaseController extends AdminBaseController {

    public function __construct($service) {
        parent::__construct($service);
    }

    public function switchBlock($id) {
        try {
            $result = $this->service->switchBlock($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_blocked' => $result,
            ]);
        } catch (Exception $e) {
            return $this->respondWithFail($e->getMessage());
        }
    }
   
}
