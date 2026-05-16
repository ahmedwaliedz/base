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
        return parent::index($request, $where)->withCount('replays');
    }

    public function switchIsRead(int|string $id): bool
    {
        $message = ContactMessage::query()->findOrFail($id);
        $message->update(['is_read' => ! $message->is_read]);

        return (bool) $message->fresh()->is_read;
    }
}