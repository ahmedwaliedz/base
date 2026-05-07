@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/home.css') }}">
@endpush

@section('content')
@php
    $admin = auth('admin')->user();
    /* Direction-aware arrow glyph (← in RTL = "go forward to that page") */
    $isRtl = in_array(app()->getLocale(), ['ar', 'fa', 'he', 'ur']);
    $arrow = $isRtl ? '←' : '→';
@endphp

{{-- ═══════════════ WELCOME BANNER ═══════════════ --}}
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

{{-- ═══════════════ USER STATS ═══════════════ --}}
<div class="dash-section-label">{{ __('admin/main.home_section_users') }}</div>
<div class="row g-3 mb-4">

    {{-- Total Users --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-users"></i></div>
                @php $chNew = $stats['change_new_users']; @endphp
                <span class="dsc__change {{ $chNew['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chNew['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chNew['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_all_time') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Users --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-active h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-user-check"></i></div>
                <span class="dsc__change dsc__change--neutral">
                    {{ $stats['ratio_users'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_active_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['active_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_of_total') }} {{ $stats['total_users'] }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- New This Month --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-new h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-user-plus"></i></div>
                <span class="dsc__change {{ $chNew['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chNew['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chNew['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_new_this_month') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['new_this_month'] }}">0</div>
                <div class="dsc__sub">{{ now()->translatedFormat('F Y') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_new_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Blocked Users — high values are BAD: ↑ red, ↓ green --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-blocked h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-user-x"></i></div>
                @php $chBlk = $stats['change_blocked']; @endphp
                {{-- For "blocked": up = bad (red), down = good (green) --}}
                <span class="dsc__change {{ $chBlk['up'] ? 'dsc__change--down' : 'dsc__change--up' }}">
                    <i class="ti {{ $chBlk['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $stats['ratio_blocked'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_blocked_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['blocked_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_restricted') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_blocked'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Users This Year --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-year h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-calendar-stats"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ now()->format('Y') }}</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_users_this_year') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['users_this_year'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_this_year') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_year_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Admins --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--admins h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-shield-check"></i></div>
                @php $chAd = $stats['change_admins']; @endphp
                @if($chAd['value'] > 0)
                    <span class="dsc__change {{ $chAd['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                        <i class="ti {{ $chAd['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                        {{ $chAd['value'] }}%
                    </span>
                @endif
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_admins') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_admins'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_admins_sub') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.admins.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_admins') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="100"></div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════ CONTENT & ACTIVITY STATS ═══════════════ --}}
<div class="dash-section-label">{{ __('admin/main.home_section_content') }}</div>
<div class="row g-3 mb-4">

    {{-- Complaints --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--complaints h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-alert-circle"></i></div>
                @php $chCompl = $stats['change_complaints']; @endphp
                {{-- For "complaints": up = bad (red), down = good (green) --}}
                <span class="dsc__change {{ $chCompl['up'] ? 'dsc__change--down' : 'dsc__change--up' }}">
                    <i class="ti {{ $chCompl['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chCompl['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_complaints') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_complaints'] }}">0</div>
                <div class="dsc__sub">{{ $stats['pending_complaints'] }} {{ __('admin/main.home_stat_pending') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_complaints'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Messages --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--contacts h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-mail"></i></div>
                @php $chCont = $stats['change_contacts']; @endphp
                <span class="dsc__change {{ $chCont['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chCont['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chCont['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_contacts') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_contacts'] }}">0</div>
                <div class="dsc__sub">+{{ $stats['new_contacts_month'] }} {{ __('admin/main.home_stat_this_month') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill"
                         data-width="{{ $stats['total_contacts'] > 0
                                        ? min((int) round($stats['new_contacts_month'] / max($stats['total_contacts'], 1) * 100 * 10), 100)
                                        : 0 }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--categories h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-tag"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_categories'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_categories') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_categories'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_categories'] }} {{ __('admin/main.home_stat_active') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_categories'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- FAQs --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--faqs h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-help-circle"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_faqs'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_faqs') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_faqs'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_faqs_sub') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_faqs'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Posts --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--posts h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-article"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_posts'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_posts') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_posts'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_posts'] }} {{ __('admin/main.home_stat_active') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_posts'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sliders --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--sliders h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-slideshow"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ $stats['ratio_sliders'] }}%</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_sliders') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_sliders'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_sliders'] }} {{ __('admin/main.home_stat_active') }}</div>
            </div>
            <div class="dsc__foot">
                <span class="dsc__link" style="opacity:.5;cursor:default">
                    {{ __('admin/main.home_card_view') }}
                </span>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_sliders'] }}"></div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════ CHARTS ROW 1 ═══════════════ --}}
<div class="row g-3 mb-3">

    {{-- Multi-series Area: Users + Admins monthly --}}
    <div class="col-12 col-xl-8">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon"
                         style="background:rgba(var(--color-brand-primary-rgb),0.15);color:var(--color-brand-primary)">
                        <i class="ti ti-chart-line" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="dash-chart__title">{{ __('admin/main.home_chart_monthly_title') }}</div>
                        <div class="dash-chart__sub">{{ __('admin/main.home_chart_monthly_sub') }}</div>
                    </div>
                </div>
                <span class="dash-chart__badge"
                      style="background:rgba(var(--color-brand-primary-rgb),0.12);color:var(--color-brand-primary)">
                    {{ __('admin/main.home_chart_last_6') }}
                </span>
            </div>
            <div class="dash-chart__body"><div id="chartMonthly"></div></div>
            <div class="dash-chart__legend">
                <div class="dash-chart__legend-item">
                    <span class="dash-chart__dot" style="background:var(--color-brand-primary)"></span>
                    {{ __('admin/main.home_stat_total_users') }}
                </div>
                <div class="dash-chart__legend-item">
                    <span class="dash-chart__dot" style="background:rgb(var(--home-users-admin-rgb))"></span>
                    {{ __('admin/main.home_stat_total_admins') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Donut: Platform distribution --}}
    <div class="col-12 col-xl-4">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon"
                         style="background:rgba(var(--color-brand-secondary-rgb),0.15);color:var(--color-brand-secondary)">
                        <i class="ti ti-chart-donut" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="dash-chart__title">{{ __('admin/main.home_chart_dist_title') }}</div>
                        <div class="dash-chart__sub">{{ __('admin/main.home_chart_dist_sub') }}</div>
                    </div>
                </div>
            </div>
            <div class="dash-chart__body"><div id="chartDist"></div></div>
            <div class="dash-chart__legend" style="justify-content:center">
                <div class="dash-chart__legend-item"><span class="dash-chart__dot" style="background:var(--color-brand-primary)"></span>{{ __('admin/main.home_stat_total_users') }}</div>
                <div class="dash-chart__legend-item"><span class="dash-chart__dot" style="background:rgb(var(--home-complaint-rgb))"></span>{{ __('admin/main.home_stat_total_complaints') }}</div>
                <div class="dash-chart__legend-item"><span class="dash-chart__dot" style="background:rgb(var(--home-contact-rgb))"></span>{{ __('admin/main.home_stat_total_contacts') }}</div>
                <div class="dash-chart__legend-item"><span class="dash-chart__dot" style="background:rgb(var(--home-category-rgb))"></span>{{ __('admin/main.home_stat_total_categories') }}</div>
                <div class="dash-chart__legend-item"><span class="dash-chart__dot" style="background:rgb(var(--home-faq-rgb))"></span>{{ __('admin/main.home_stat_total_faqs') }}</div>
                <div class="dash-chart__legend-item"><span class="dash-chart__dot" style="background:rgb(var(--home-post-rgb))"></span>{{ __('admin/main.home_stat_posts') }}</div>
                <div class="dash-chart__legend-item"><span class="dash-chart__dot" style="background:rgb(var(--home-slider-rgb))"></span>{{ __('admin/main.home_stat_sliders') }}</div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════ CHARTS ROW 2 ═══════════════ --}}
<div class="row g-3 mb-4">

    {{-- Activity chart with TABS (replaces dual-axis mixed chart) --}}
    <div class="col-12 col-xl-7">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon"
                         style="background:rgba(var(--home-complaint-rgb),0.15);color:rgb(var(--home-complaint-rgb))">
                        <i class="ti ti-chart-bar" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="dash-chart__title">{{ __('admin/main.home_chart_activity_title') }}</div>
                        <div class="dash-chart__sub">{{ __('admin/main.home_chart_activity_sub') }}</div>
                    </div>
                </div>
                <div class="dash-chart__tabs" role="tablist" aria-label="{{ __('admin/main.home_chart_activity_title') }}">
                    <button type="button" class="dash-chart__tab is-active"
                            data-tab="complaints" role="tab" aria-selected="true">
                        {{ __('admin/main.home_stat_total_complaints') }}
                    </button>
                    <button type="button" class="dash-chart__tab"
                            data-tab="contacts" role="tab" aria-selected="false">
                        {{ __('admin/main.home_stat_total_contacts') }}
                    </button>
                    <button type="button" class="dash-chart__tab"
                            data-tab="users" role="tab" aria-selected="false">
                        {{ __('admin/main.home_stat_total_users') }}
                    </button>
                </div>
            </div>
            <div class="dash-chart__body"><div id="chartActivity"></div></div>
        </div>
    </div>

    {{-- Polar Area: Active ratios — same unit (%) only --}}
    <div class="col-12 col-xl-5">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon"
                         style="background:rgba(var(--color-success-rgb),0.15);color:var(--color-success)">
                        <i class="ti ti-chart-radar" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="dash-chart__title">{{ __('admin/main.home_chart_polar_title') }}</div>
                        <div class="dash-chart__sub">{{ __('admin/main.home_chart_polar_sub') }}</div>
                    </div>
                </div>
            </div>
            <div class="dash-chart__body"><div id="chartPolar"></div></div>
        </div>
    </div>

</div>

{{-- ═══════════════ QUICK-ACTION TABLES ═══════════════ --}}
<div class="dash-section-label">{{ __('admin/main.home_section_tables') }}</div>
<div class="row g-3 mb-4">

    {{-- Latest Users --}}
    <div class="col-12 col-xl-6">
        <div class="dqt h-100">
            <div class="dqt__head">
                <div class="dqt__head-left">
                    <div class="dqt__icon"
                         style="background:rgba(var(--color-brand-primary-rgb),.14);color:var(--color-brand-primary)">
                        <i class="ti ti-users" aria-hidden="true"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_latest_users') }}</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="dqt__view-all">
                    {{ __('admin/main.home_table_view_all') }} <span class="dqt__arrow">{{ $arrow }}</span>
                </a>
            </div>
            <table class="dqt__table">
                <thead>
                    <tr>
                        <th>{{ __('admin/main.home_table_col_user') }}</th>
                        <th>{{ __('admin/main.home_table_col_phone') }}</th>
                        <th>{{ __('admin/main.home_table_col_status') }}</th>
                        <th>{{ __('admin/main.home_table_col_date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['latest_users'] as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($u->image)
                                    <img src="{{ asset('storage/'.$u->image) }}"
                                         class="dqt__avatar"
                                         alt="{{ $u->name }}">
                                @else
                                    <span class="dqt__avatar-fallback" aria-hidden="true">
                                        {{ mb_substr($u->name, 0, 1) }}
                                    </span>
                                @endif
                                <div class="dqt__name">{{ $u->name }}</div>
                            </div>
                        </td>
                        <td><span class="dqt__sub">{{ $u->phone ?? '—' }}</span></td>
                        <td>
                            @if($u->is_blocked)
                                <span class="dqt__badge dqt__badge--blocked">
                                    <i class="ti ti-lock" aria-hidden="true"></i>
                                    {{ __('admin/main.home_badge_blocked') }}
                                </span>
                            @else
                                <span class="dqt__badge dqt__badge--active">
                                    <i class="ti ti-circle-check" aria-hidden="true"></i>
                                    {{ __('admin/main.home_badge_active') }}
                                </span>
                            @endif
                        </td>
                        <td><span class="dqt__date">{{ $u->created_at->diffForHumans() }}</span></td>
                        <td>
                            <a href="{{ route('admin.users.index') }}" class="dqt__action"
                               aria-label="{{ __('admin/main.home_action_view') }}">
                                <i class="ti ti-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="dqt__empty">
                                <i class="ti ti-users" aria-hidden="true"></i>
                                {{ __('admin/main.home_table_empty') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Complaints --}}
    <div class="col-12 col-xl-6">
        <div class="dqt h-100">
            <div class="dqt__head">
                <div class="dqt__head-left">
                    <div class="dqt__icon"
                         style="background:rgba(var(--home-complaint-rgb),.14);color:rgb(var(--home-complaint-rgb))">
                        <i class="ti ti-alert-circle" aria-hidden="true"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_pending_complaints') }}</span>
                </div>
                <span class="dqt__view-all dqt__view-all--disabled">
                    {{ __('admin/main.home_table_view_all') }}
                </span>
            </div>
            <table class="dqt__table">
                <thead>
                    <tr>
                        <th>{{ __('admin/main.home_table_col_name') }}</th>
                        <th>{{ __('admin/main.home_table_col_subject') }}</th>
                        <th>{{ __('admin/main.home_table_col_status') }}</th>
                        <th>{{ __('admin/main.home_table_col_date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['pending_complaints_list'] as $c)
                    @php
                        $statusMap = [
                            'pending'    => ['class' => 'dqt__badge--pending', 'icon' => 'ti-clock',        'label' => __('admin/main.home_badge_pending')],
                            'processing' => ['class' => 'dqt__badge--process', 'icon' => 'ti-refresh',      'label' => __('admin/main.home_badge_processing')],
                            'completed'  => ['class' => 'dqt__badge--done',    'icon' => 'ti-circle-check', 'label' => __('admin/main.home_badge_completed')],
                            'rejected'   => ['class' => 'dqt__badge--reject',  'icon' => 'ti-x',            'label' => __('admin/main.home_badge_rejected')],
                        ];
                        $st = $statusMap[$c->status] ?? $statusMap['pending'];
                    @endphp
                    <tr>
                        <td>
                            <div class="dqt__name">{{ $c->name }}</div>
                            <div class="dqt__sub">{{ $c->phone ?? $c->email ?? '—' }}</div>
                        </td>
                        <td><span class="dqt__cell-clip">{{ $c->subject }}</span></td>
                        <td>
                            <span class="dqt__badge {{ $st['class'] }}">
                                <i class="ti {{ $st['icon'] }}" aria-hidden="true"></i>
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td><span class="dqt__date">{{ \Carbon\Carbon::parse($c->created_at)->diffForHumans() }}</span></td>
                        <td>
                            <span class="dqt__action dqt__action--disabled" aria-hidden="true">
                                <i class="ti ti-eye"></i>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="dqt__empty">
                                <i class="ti ti-circle-check" aria-hidden="true"></i>
                                {{ __('admin/main.home_table_no_pending') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="row g-3 mb-4">

    {{-- Latest Contact Messages --}}
    <div class="col-12">
        <div class="dqt">
            <div class="dqt__head">
                <div class="dqt__head-left">
                    <div class="dqt__icon"
                         style="background:rgba(var(--home-contact-rgb),.14);color:rgb(var(--home-contact-rgb))">
                        <i class="ti ti-mail" aria-hidden="true"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_latest_contacts') }}</span>
                </div>
                <span class="dqt__view-all dqt__view-all--disabled">
                    {{ __('admin/main.home_table_view_all') }}
                </span>
            </div>
            <table class="dqt__table">
                <thead>
                    <tr>
                        <th>{{ __('admin/main.home_table_col_name') }}</th>
                        <th>{{ __('admin/main.home_table_col_email') }}</th>
                        <th>{{ __('admin/main.home_table_col_phone') }}</th>
                        <th>{{ __('admin/main.home_table_col_subject') }}</th>
                        <th>{{ __('admin/main.home_table_col_date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['latest_contacts'] as $m)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="dqt__avatar-fallback"
                                      style="background:linear-gradient(135deg,rgb(var(--home-contact-rgb)),#64b5f6)"
                                      aria-hidden="true">
                                    {{ mb_substr($m->name, 0, 1) }}
                                </span>
                                <span class="dqt__name">{{ $m->name }}</span>
                            </div>
                        </td>
                        <td><span class="dqt__sub">{{ $m->email ?? '—' }}</span></td>
                        <td><span class="dqt__sub">{{ $m->phone ?? '—' }}</span></td>
                        <td><span class="dqt__cell-clip">{{ $m->subject }}</span></td>
                        <td><span class="dqt__date">{{ \Carbon\Carbon::parse($m->created_at)->diffForHumans() }}</span></td>
                        <td>
                            <span class="dqt__action dqt__action--disabled" aria-hidden="true">
                                <i class="ti ti-eye"></i>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="dqt__empty">
                                <i class="ti ti-mail" aria-hidden="true"></i>
                                {{ __('admin/main.home_table_empty') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js" defer></script>
<script>
    window.HOME_STATS = {
        monthly_labels:        @json($stats['monthly_labels']),
        users_monthly_counts:  @json($stats['monthly_counts']),
        admins_monthly_counts: @json($stats['admin_monthly_counts']),

        activity_labels:     @json($stats['activity_labels']),
        activity_complaints: @json($stats['activity_complaints']),
        activity_contacts:   @json($stats['activity_contacts']),

        dist_series: @json($stats['dist_series']),
        dist_labels: [
            @json(__('admin/main.home_stat_total_users')),
            @json(__('admin/main.home_stat_total_complaints')),
            @json(__('admin/main.home_stat_total_contacts')),
            @json(__('admin/main.home_stat_total_categories')),
            @json(__('admin/main.home_stat_total_faqs')),
            @json(__('admin/main.home_stat_posts')),
            @json(__('admin/main.home_stat_sliders')),
        ],

        polar_series: @json($stats['polar_series']),
        polar_labels: [
            @json(__('admin/main.home_stat_active_users')),
            @json(__('admin/main.home_stat_total_categories')),
            @json(__('admin/main.home_stat_sliders')),
            @json(__('admin/main.home_chart_resolved')),
        ],

        labels: {
            users:      @json(__('admin/main.home_stat_total_users')),
            admins:     @json(__('admin/main.home_stat_total_admins')),
            complaints: @json(__('admin/main.home_stat_total_complaints')),
            contacts:   @json(__('admin/main.home_stat_total_contacts')),
            total:      @json(__('admin/main.home_chart_total')),
        }
    };
</script>
<script src="{{ asset('style/admin/js/home.js') }}" defer></script>
@endpush
