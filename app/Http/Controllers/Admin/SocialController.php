<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\SocialService;
use Illuminate\Http\Request;

class SocialController extends AdminBaseController
{
    public function __construct(SocialService $socialService)
    {
        parent::__construct($socialService);
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