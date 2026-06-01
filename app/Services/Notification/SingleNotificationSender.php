<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Notifications\UserNotification;

class SingleNotificationSender
{
    /**
     * Send a notification to a specific user.
     *
     * @param int $userId The ID of the user to send to
     * @param array $message The message to send
     * @param string $modelClass The model class to query
     * @return void
     */
    public function sendToUserId( string $modelClass = User::class , int $userId = 1 , array $message = [] , string $notification_type = 'admin_notification'): void
    {
        $modelClass::find($userId)?->notify(new UserNotification([
            'message' => $message,
            'notification_type' => $notification_type,
        ]));
    }
}
