<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\ContactMessageService;
use Illuminate\Http\Request;

class ContactMessageController extends AdminBaseController
{
    public function __construct(ContactMessageService $contactMessageService)
    {
        parent::__construct($contactMessageService);
    }

    public function switchIsRead(Request $request, $id)
    {
        try {
            $isRead = $this->service->switchIsRead($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_read' => $isRead,
            ]);
        } catch (\Throwable $e) {
            return $this->respondInternalError();
        }
    }
}