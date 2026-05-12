@php
    $admin = auth('admin')->user();
@endphp
<li class="nav-item navbar-dropdown dropdown-user dropdown">
    <a class="nav-link dropdown-toggle hide-arrow d-inline-flex align-items-center"
       href="javascript:void(0);"
       data-bs-toggle="dropdown"
       aria-label="{{ $admin?->name }}">

        <div class="nav-profile-meta">
            <span class="nav-profile-meta__name">{{ $admin?->name }}</span>
            <span class="nav-profile-meta__role">{{ $admin?->role_name }}</span>
        </div>

        <div class="avatar avatar-online">
            <img src="{{ $admin?->image_url }}" alt="" class="h-auto rounded-circle"/>
        </div>
    </a>

    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                <div class="d-flex align-items-center gap-2">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-online">
                            <img src="{{ $admin?->image_url }}" alt="" class="h-auto rounded-circle"/>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <span class="fw-semibold d-block text-truncate">{{ $admin?->name }}</span>
                        <small class="text-muted">{{ $admin?->role_name }}</small>
                    </div>
                </div>
            </a>
        </li>
        <li><div class="dropdown-divider"></div></li>
        <li>
            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                <i class="ti ti-user-check me-2" aria-hidden="true"></i>
                <span class="align-middle">{{ __('admin/main.profile') }}</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                <i class="ti ti-settings me-2" aria-hidden="true"></i>
                <span class="align-middle">{{ __('admin/main.settings') }}</span>
            </a>
        </li>
        <li><div class="dropdown-divider"></div></li>
        <li>
            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item text-danger w-100 text-start">
                    <i class="ti ti-logout me-2" aria-hidden="true"></i>
                    <span class="align-middle">{{ __('admin/auth.logout') }}</span>
                </button>
            </form>
        </li>
    </ul>
</li>
