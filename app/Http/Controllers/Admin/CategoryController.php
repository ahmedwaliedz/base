<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends AdminBaseController
{
    public function __construct(CategoryService $categoryService)
    {
        parent::__construct($categoryService);
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