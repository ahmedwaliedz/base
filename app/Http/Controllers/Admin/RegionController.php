<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\RegionService;
use Illuminate\Http\Request;

class RegionController extends AdminBaseController
{
    public function __construct(RegionService $regionService)
    {
        parent::__construct($regionService);
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