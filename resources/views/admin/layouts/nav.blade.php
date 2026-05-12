@php
    $admin = auth('admin')->user();
    $unreadCount = $admin?->unreadNotifications->count() ?? 0;
@endphp

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">

    {{-- ═══════════ START SIDE: mobile-menu toggle + theme switcher + status ═══════════ --}}
    <div class="d-flex align-items-center gap-1">
        {{-- Mobile menu toggle (only <xl) --}}
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-1 me-xl-0 d-xl-none">
            <a class="nav-item nav-link" href="javascript:void(0)" aria-label="{{ __('admin/main.menu') }}">
                <i class="ti ti-menu-2" aria-hidden="true"></i>
            </a>
        </div>

        <ul class="navbar-nav flex-row align-items-center">
            @include('admin.layouts.parts.brand-switch')
            @include('admin.layouts.parts.style-switch')
        </ul>

        {{-- System status indicator (lg+) --}}
        <span class="nav-system-status d-none d-lg-inline-flex"
              title="{{ __('admin/main.home_chip_server_tooltip') }}">
            <span class="nav-system-status__dot" aria-hidden="true"></span>
            <span class="nav-system-status__label">{{ __('admin/main.home_chip_server_ok') }}</span>
        </span>
    </div>

    {{-- ═══════════ END SIDE: quick links + language + fullscreen + bell + profile ═══════════ --}}
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            {{-- Quick links (dashboard / users / settings / notifications) --}}
            <li class="nav-item nav-item-quick-links dropdown d-none d-md-block">
                <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown"
                   aria-label="{{ __('admin/main.quick_links') }}">
                    <i class="ti ti-apps" aria-hidden="true"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end nav-quick-links-menu">
                    <div class="nav-quick-links-menu__title">{{ __('admin/main.quick_links') }}</div>
                    <div class="nav-quick-links">
                        <a href="{{ route('admin.home') }}" class="nav-quick-link" data-link="dashboard">
                            <span class="nav-quick-link__icon" aria-hidden="true"><i class="ti ti-home-2"></i></span>
                            <span class="nav-quick-link__label">{{ __('admin/main.home_section_users') }}</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="nav-quick-link" data-link="users">
                            <span class="nav-quick-link__icon" aria-hidden="true"><i class="ti ti-users"></i></span>
                            <span class="nav-quick-link__label">{{ __('admin/main.home_stat_total_users') }}</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="nav-quick-link" data-link="settings">
                            <span class="nav-quick-link__icon" aria-hidden="true"><i class="ti ti-settings"></i></span>
                            <span class="nav-quick-link__label">{{ __('admin/main.settings') }}</span>
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="nav-quick-link" data-link="notifications">
                            <span class="nav-quick-link__icon" aria-hidden="true"><i class="ti ti-bell"></i></span>
                            <span class="nav-quick-link__label">{{ __('admin/main.notifications') }}</span>
                        </a>
                    </div>
                </div>
            </li>

            @include('admin.layouts.parts.language')

            {{-- Fullscreen toggle (lg+) --}}
            <li class="nav-item nav-item-fullscreen d-none d-lg-block">
                <a class="nav-link" href="javascript:void(0);" id="navFullscreenToggle"
                   aria-label="{{ __('admin/main.fullscreen') }}">
                    <i class="ti ti-arrows-maximize" aria-hidden="true"></i>
                </a>
            </li>

            @include('admin.layouts.parts.notifications')
            @include('admin.layouts.parts.profile-dropdown')
        </ul>
    </div>
</nav>

<script>
    (function () {
        // Fullscreen toggle
        var btn = document.getElementById('navFullscreenToggle');
        if (!btn) return;
        var icon = btn.querySelector('i');
        function syncIcon() {
            if (!icon) return;
            if (document.fullscreenElement) {
                icon.classList.remove('ti-arrows-maximize');
                icon.classList.add('ti-arrows-minimize');
            } else {
                icon.classList.remove('ti-arrows-minimize');
                icon.classList.add('ti-arrows-maximize');
            }
        }
        btn.addEventListener('click', function () {
            if (!document.fullscreenElement) {
                (document.documentElement.requestFullscreen
                    || document.documentElement.webkitRequestFullscreen
                    || function () {}).call(document.documentElement);
            } else {
                (document.exitFullscreen
                    || document.webkitExitFullscreen
                    || function () {}).call(document);
            }
        });
        document.addEventListener('fullscreenchange', syncIcon);
        document.addEventListener('webkitfullscreenchange', syncIcon);
    })();
</script>
