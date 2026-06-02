<?php

namespace App\Services\Admin;

use App\Models\ContactMessage;
use App\Services\Admin\Base\CrudBaseService;

class ContactMessageService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(ContactMessage::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->withTrashed()->withCount('replays');
    }

    public function show($id)
    {
        $result = parent::show($id);

        $message = $result['model'] ?? null;
        if ($message && ! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return $result;
    }

    public function switchIsRead(int|string $id): bool
    {
        $message = ContactMessage::query()->withTrashed()->findOrFail($id);
        $message->update(['is_read' => ! $message->is_read]);

        return (bool) $message->fresh()->is_read;
    }
}
