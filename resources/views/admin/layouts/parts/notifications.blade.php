@php
    $unread = auth('admin')->user()?->unreadNotifications->count() ?? 0;
@endphp
<li class="nav-item dropdown-notifications">
    <a class="nav-link" href="{{ route('admin.notifications.index') }}"
       aria-label="{{ __('admin/main.notifications') }}{{ $unread > 0 ? ' ('.$unread.')' : '' }}">
        <i class="ti ti-bell" aria-hidden="true"></i>
        @if($unread > 0)
            <span class="badge-notifications" aria-hidden="true">
                {{ $unread > 99 ? '99+' : $unread }}
            </span>
        @endif
    </a>
</li>
