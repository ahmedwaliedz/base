<?php

namespace App\Services\Notification;

use App\Models\Admin;
use App\Models\User;

readonly class NotificationService
{
    /**
     * Create a new notification service instance.
     *
     * @param GroupNotificationSender $groupSender The group notification sender
     * @param SingleNotificationSender $singleSender The single notification sender
     */
    public function __construct(
        private GroupNotificationSender  $groupSender,
        private SingleNotificationSender $singleSender
    ) {
    }

    /**
     * Get view data for the notification index page.
     */
    public function getNotificationPageData(): array
    {
        return [
            'availableUserNotificationTypes' => User::getAvailableNotificationTypes(),
            'availableAdminNotificationTypes' => Admin::getAvailableNotificationTypes(),
        ];
    }

    /**
     * Send a notification based on the provided data.
     *
     * @param array $data The notification data
     * @return bool Whether the notification was sent successfully
     */
    public function send(array $data): bool
    {
        if (is_string($data['id']) && strtolower($data['id']) === 'group') {
            $this->groupSender->sendToAll(
                modelClass           : $data['class'],
                userType             : $data['user_type'],
                notifyData           : [
                    'message'             => $data['message'],
                    'notification_type'   => $data['notification_type']

                ]
            );
            return true;
        }elseif (is_numeric($data['id'])) {
            $this->singleSender->sendToUserId(
                modelClass  : $data['class'],
                userId      : (int) $data['id'],
                message     : $data['message'],
                notification_type  : $data['notification_type'],
            );
            return true;
        }else{
            return false;
        }
    }
}
