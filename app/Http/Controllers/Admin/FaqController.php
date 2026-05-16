<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\FaqService;
use Illuminate\Http\Request;

class FaqController extends AdminBaseController
{
    public function __construct(FaqService $faqService)
    {
        parent::__construct($faqService);
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