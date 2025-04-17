<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
    <a class="nav-link dropdown-toggle hide-arrow" href="{{route('admin.notifications')}}" >
        <i class="ti ti-bell ti-md"></i>
        @if(auth('admin')->user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger rounded-pill badge-notifications">
                {{ auth('admin')->user()->unreadNotifications->count()}}
            </span>
        @endif
    </a>
</li>
