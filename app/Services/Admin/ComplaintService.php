<?php

namespace App\Services\Admin;

use App\Enums\ComplaintStatus;
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
        return parent::index($request, $where)->withTrashed()->withCount('replays', 'images');
    }

    public function switchIsRead(int|string $id): bool
    {
        $complaint = Complaint::query()->withTrashed()->findOrFail($id);
        $complaint->update(['is_read' => ! $complaint->is_read]);

        return (bool) $complaint->fresh()->is_read;
    }

    public function switchStatus(int|string $id): string
    {
        $complaint = Complaint::query()->withTrashed()->findOrFail($id);

        $cases = ComplaintStatus::cases();
        $currentIndex = array_search($complaint->status, $cases, true);
        $nextIndex = ($currentIndex === false ? 0 : ($currentIndex + 1) % count($cases));
        $nextStatus = $cases[$nextIndex];

        $complaint->update(['status' => $nextStatus->value]);

        return $nextStatus->value;
    }
}
