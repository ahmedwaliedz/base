<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\CityService;
use Illuminate\Http\Request;

class CityController extends AdminBaseController
{
    public function __construct(CityService $cityService)
    {
        parent::__construct($cityService);
    }

    public function switchIsActive(Request $request, $id)
    {
        try {
            $isActive = $this->service->switchIsActive($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_active' => $isActive,
            ]);
        } catch (\Throwable $e) {
            return $this->respondInternalError();
        }
    }
}