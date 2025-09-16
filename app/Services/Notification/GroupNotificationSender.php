<?php

namespace App\Services\Notification;

use App\Enums\ModelNotificationType;
use App\Jobs\SendNotificationJob;
use App\Models\User;

class GroupNotificationSender
{
    public function sendToAll(string $modelClass = User::class, string $userType = 'all' , array $notifyData = []): void
    {
        $query = $modelClass::query();
        if ($userType !== 'all') {
            match ($userType) {
                ModelNotificationType::BLOCKED->value       => $query->where('is_blocked', true),
                ModelNotificationType::NOTBLOCKED->value    => $query->where('is_blocked', false),
                ModelNotificationType::ACTIVE->value        => $query->where('is_active', true),
                ModelNotificationType::NOTACTIVE->value     => $query->where('is_active', false),
                default => null,
            };
        }
        $rows = $query->get();
        dispatch(new SendNotificationJob($rows, $notifyData));
    }
}
