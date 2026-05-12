{{-- Welcome banner --}}
<div class="dash-welcome mb-4">
    <div class="dash-welcome__orb dash-welcome__orb--1" aria-hidden="true"></div>
    <div class="dash-welcome__orb dash-welcome__orb--2" aria-hidden="true"></div>
    <div class="dash-welcome__orb dash-welcome__orb--3" aria-hidden="true"></div>

    <div class="dash-welcome__date" aria-hidden="true">
        <span class="dash-welcome__date-day">{{ now()->format('d') }}</span>
        <span>{{ now()->translatedFormat('M Y') }}</span>
    </div>

    <div class="dash-welcome__content">
        <div class="dash-welcome__greeting">{{ __('admin/main.' . $stats['greeting_key']) }}</div>
        <div class="dash-welcome__name">{{ $admin->name }}</div>
        <p class="dash-welcome__subtitle">{{ __('admin/main.home_welcome_subtitle') }}</p>

        <div class="dash-welcome__chips">
            <span class="dash-welcome__chip" title="{{ __('admin/main.home_chip_today_tooltip') }}">
                <i class="ti ti-user-plus" aria-hidden="true"></i>
                +{{ number_format($stats['new_today']) }} {{ __('admin/main.home_chip_today') }}
            </span>

            @if($admin->last_login_at ?? false)
                <span class="dash-welcome__chip" title="{{ __('admin/main.home_chip_last_login_tooltip') }}">
                    <i class="ti ti-clock" aria-hidden="true"></i>
                    {{ \Carbon\Carbon::parse($admin->last_login_at)->diffForHumans() }}
                </span>
            @endif

            @if($stats['pending_complaints'] > 0)
                <span class="dash-welcome__chip" title="{{ __('admin/main.home_chip_pending_tooltip') }}">
                    <i class="ti ti-bell" aria-hidden="true"></i>
                    {{ number_format($stats['pending_complaints']) }} {{ __('admin/main.home_chip_pending') }}
                </span>
            @endif

            <span class="dash-welcome__chip" title="{{ __('admin/main.home_chip_server_tooltip') }}">
                <i class="ti ti-server-bolt" aria-hidden="true"></i>
                {{ __('admin/main.home_chip_server_ok') }}
            </span>
        </div>
    </div>
</div>
