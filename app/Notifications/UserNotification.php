<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * User Notification
 *
 * This notification is used to send messages to users via different channels (database, mail, sms).
 */
class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected  array $notifyData)
    {

    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        if (isset($this->notifyData['notification_type']) && $this->notifyData['notification_type'] === 'mail') {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $message = $this->notifyData['message'][app()->getLocale()] ?? $this->notifyData['message']['en'] ?? '';

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject(__('admin/main.notification_from_admin'))
            ->line($message);
    }

    public function toArray($notifiable): array
    {
        return $this->notifyData;
    }
}
