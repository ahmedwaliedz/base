<?php

namespace App\Enums;

enum ModelNotificationType: string
{
    case ACTIVE = 'active';
    case NOTACTIVE = 'not_active';

    case BLOCKED = 'blocked';

    case NOTBLOCKED = 'not_blocked';

    /**
     * Get the human-readable label for the notification type
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('admin/main.is_active_notification'),
            self::NOTACTIVE => __('admin/main.is_not_active_notification'),
            self::BLOCKED => __('admin/main.is_blocked_notification'),
            self::NOTBLOCKED => __('admin/main.is_not_blocked_notification'),
        };
    }
}
