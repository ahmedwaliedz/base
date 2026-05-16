<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\DistrictService;
use Illuminate\Http\Request;

class DistrictController extends AdminBaseController
{
    public function __construct(DistrictService $districtService)
    {
        parent::__construct($districtService);
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