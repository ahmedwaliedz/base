<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;

class AppNotificationService
{
    public function dashboardData(Admin $admin, int $limit = 5): array
    {
        return [
            'adminNotificationSummary' => $this->getSummary($admin),
            'adminLatestNotifications' => $this->latestNotifications($admin, $limit),
        ];
    }

    public function getNotificationsData(Admin $admin, int $limit = 5, int $perPage = 15): array
    {
        $summary = $this->getSummary($admin);

        return [
            'summary' => $summary,
            'notifications' => $this->paginate($admin, $perPage),
            'latestNotifications' => $this->latestNotifications($admin, $limit),
        ];
    }

    public function getSummary(Admin $admin): array
    {
        $total = $admin->notifications()->count();
        $unread = $admin->unreadNotifications()->count();

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => max(0, $total - $unread),
        ];
    }

    public function paginate(Admin $admin, int $perPage = 15): LengthAwarePaginator
    {
        return $admin->notifications()
            ->latest()
            ->paginate($perPage)
            ->through(fn (DatabaseNotification $notification) => $this->formatNotification($notification));
    }

    public function markAsRead(Admin $admin, string $notificationId): bool
    {
        $notification = $admin->notifications()->whereKey($notificationId)->first();

        if (! $notification) {
            return false;
        }

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        return true;
    }

    public function markAllAsRead(Admin $admin): array
    {
        $notifications = $admin->unreadNotifications()->get();
        $count = $notifications->count();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return [
            'count' => $count,
            'latestNotifications' => $this->latestNotifications($admin)->values()->all(),
        ];
    }

    public function latestNotifications(Admin $admin, int $limit = 5): Collection
    {
        return $admin->notifications()
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn (DatabaseNotification $notification) => $this->formatNotification($notification))
            ->values();
    }

    protected function formatNotification(DatabaseNotification $notification): array
    {
        $payload = is_array($notification->data) ? $notification->data : [];
        $type = (string) data_get($payload, 'notification_type', 'admin_notification');

        return [
            'id' => $notification->id,
            'type' => $type,
            'type_label' => $this->typeLabel($type),
            'title' => $this->title($payload, $type),
            'message' => $this->message($payload),
            'icon' => $this->typeIcon($type),
            'tone' => $this->typeTone($type),
            'is_read' => (bool) $notification->read_at,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at,
            'created_human' => $notification->created_at?->diffForHumans(),
            'created_date' => $this->formatDate($notification->created_at),
        ];
    }

    protected function message(array $payload): string
    {
        $locale = adminLang();

        return (string) (
            data_get($payload, "message.{$locale}")
            ?? data_get($payload, 'message.en')
            ?? data_get($payload, 'message.ar')
            ?? data_get($payload, 'message')
            ?? __('admin/main.no_data_found')
        );
    }

    protected function title(array $payload, string $type): string
    {
        $locale = adminLang();

        return (string) (
            data_get($payload, "title.{$locale}")
            ?? data_get($payload, 'title.en')
            ?? data_get($payload, 'title.ar')
            ?? data_get($payload, 'title')
            ?? $this->typeLabel($type)
        );
    }

    protected function typeLabel(string $type): string
    {
        return match ($type) {
            'mail' => __('admin/main.email_channel'),
            'sms' => __('admin/main.sms_channel'),
            default => __('admin/main.in_app'),
        };
    }

    protected function typeIcon(string $type): string
    {
        return match ($type) {
            'mail' => 'ti ti-mail',
            'sms' => 'ti ti-message-dots',
            default => 'ti ti-bell',
        };
    }

    protected function typeTone(string $type): string
    {
        return match ($type) {
            'mail' => 'mail',
            'sms' => 'sms',
            default => 'default',
        };
    }

    protected function formatDate(CarbonInterface|null $date): string
    {
        return $date ? $date->format('Y-m-d H:i') : '-';
    }
}
