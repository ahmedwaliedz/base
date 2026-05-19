@php
    $summary = $adminNotificationSummary ?? ['total' => 0, 'unread' => 0, 'read' => 0];
    $notifications = $adminLatestNotifications ?? collect();
    $unread = $summary['unread'] ?? 0;
@endphp
<li class="nav-item dropdown-notifications dropdown">
    <a class="nav-link dropdown-toggle hide-arrow"
       href="javascript:void(0);"
       data-bs-toggle="dropdown"
       aria-expanded="false"
       aria-label="{{ __('admin/main.notifications') }}{{ $unread > 0 ? ' (' . $unread . ')' : '' }}">
        <i class="ti ti-bell" aria-hidden="true"></i>
        @if($unread > 0)
            <span class="badge-notifications" aria-hidden="true">
                {{ $unread > 99 ? '99+' : $unread }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-notifications-menu">
        <div class="dropdown-notifications__header">
            <div class="dropdown-notifications__header-count">
                <span class="dropdown-notifications__header-count-number">{{ $unread }}</span>
                <span class="dropdown-notifications__header-count-label">{{ __('admin/main.new') }}</span>
            </div>
            <div class="dropdown-notifications__header-title">{{ __('admin/main.app_notifications') }}</div>
        </div>

        <div class="dropdown-notifications__body">
            @forelse ($notifications as $notification)
                <a class="dropdown-notifications__item dropdown-notifications__item--{{ $notification['tone'] }} {{ $notification['is_read'] ? 'is-read' : 'is-unread' }}"
                   href="{{ route('admin.app-notifications.index') }}">
                    <span class="dropdown-notifications__state" aria-hidden="true"></span>
                    <span class="dropdown-notifications__icon dropdown-notifications__icon--{{ $notification['tone'] }}">
                        <i class="{{ $notification['icon'] }}" aria-hidden="true"></i>
                    </span>
                    <span class="dropdown-notifications__content">
                        <span class="dropdown-notifications__topline">
                            <span class="dropdown-notifications__item-title">{{ $notification['title'] }}</span>
                            <span class="dropdown-notifications__time">{{ $notification['created_human'] }}</span>
                        </span>
                        <span class="dropdown-notifications__message">{{ $notification['message'] }}</span>
                    </span>
                    <span class="dropdown-notifications__read-tag">
                        {{ $notification['is_read'] ? __('admin/main.read_notifications') : __('admin/main.unread_notifications') }}
                    </span>
                </a>
            @empty
                <div class="dropdown-notifications__empty">
                    <i class="ti ti-bell-off" aria-hidden="true"></i>
                    <span>{{ __('admin/main.no_notifications_yet') }}</span>
                </div>
            @endforelse
        </div>

        <div class="dropdown-notifications__footer">
            <a href="{{ route('admin.app-notifications.index') }}">
                <span>{{ __('admin/main.read_all_notifications') }}</span>
            </a>
        </div>
    </div>
</li>
