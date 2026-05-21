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
            $type = $this->service->switchType($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'type' => $type->value,
            ]);
        } catch (\Throwable $e) {
            return $this->respondInternalError();
        }
    }
}