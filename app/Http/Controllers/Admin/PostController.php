<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends AdminBaseController
{
    public function __construct(PostService $postService)
    {
        parent::__construct($postService);
    }

    public function switchIsActive(Request $request, $id)
    {
        try {
            $isActive = $this->service->switchIsActive($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_active' => $isActive,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        } catch (\Throwable $e) {
            return $this->respondInternalError();
        }
    }
}