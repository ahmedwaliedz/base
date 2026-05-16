<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\PageService;
use Illuminate\Http\Request;

class PageController extends AdminBaseController
{
    public function __construct(PageService $pageService)
    {
        parent::__construct($pageService);
    }

    public function switchType(Request $request, $id)
    {
        try {
            $isActive = $this->service->switchType($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_active' => $isActive,
            ]);
        } catch (\Throwable $e) {
            return $this->respondInternalError();
        }
    }
}