<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\ComplaintService;
use Illuminate\Http\Request;

class ComplaintController extends AdminBaseController
{
    public function __construct(ComplaintService $complaintService)
    {
        parent::__construct($complaintService);
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

    public function switchStatus(Request $request, $id)
    {
        try {
            $status = $this->service->switchStatus($id);
            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'status' => $status,
            ]);
        } catch (\Throwable $e) {
            return $this->respondInternalError();
        }
    }
}