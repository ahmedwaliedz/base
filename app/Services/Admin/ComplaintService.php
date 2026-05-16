<?php

namespace App\Services\Admin;

use App\Models\Complaint;
use App\Services\Admin\Base\CrudBaseService;

class ComplaintService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Complaint::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->withCount('replays', 'images');
    }

    public function switchIsRead(int|string $id): bool
    {
        $complaint = Complaint::query()->findOrFail($id);
        $complaint->update(['is_read' => ! $complaint->is_read]);

        return (bool) $complaint->fresh()->is_read;
    }

    public function switchStatus(int|string $id): bool
    {
        $complaint = Complaint::query()->findOrFail($id);
        $complaint->update(['status' => ! $complaint->status]);

        return (bool) $complaint->fresh()->status;
    }
}