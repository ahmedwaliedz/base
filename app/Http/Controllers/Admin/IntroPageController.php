<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\IntroPageService;
use Illuminate\Http\Request;

class IntroPageController extends AdminBaseController
{
    public function __construct(IntroPageService $introPageService)
    {
        parent::__construct($introPageService);
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