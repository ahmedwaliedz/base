<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\SliderService;
use Illuminate\Http\Request;

class SliderController extends AdminBaseController
{
    public function __construct(SliderService $sliderService)
    {
        parent::__construct($sliderService);
    }

    public function switchActive(Request $request, $id)
    {
        try {
            $isActive = $this->service->switchActive($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_active' => $isActive,
            ]);
        } catch (\Throwable $e) {
            return $this->respondInternalError();
        }
    }
}