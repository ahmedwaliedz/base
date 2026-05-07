@extends('admin.layouts.master')

@push('css')
<style>
/* ═══════════════════════════════════════════════════════════
   WELCOME BANNER
═══════════════════════════════════════════════════════════ */
.dash-welcome {
    position: relative;
    border-radius: 1.25rem;
    overflow: hidden;
    padding: 2rem 2.25rem;
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    color: #fff;
    box-shadow: 0 12px 40px rgba(15,12,41,0.55);
    isolation: isolate;
}
.dash-welcome__orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    pointer-events: none;
    opacity: 0.45;
    animation: dash-orb-drift 8s ease-in-out infinite alternate;
}
.dash-welcome__orb--1 {
    width: 280px; height: 280px;
    background: radial-gradient(circle, #7367f0 0%, transparent 70%);
    inset-block-start: -80px; inset-inline-end: -60px;
}
.dash-welcome__orb--2 {
    width: 200px; height: 200px;
    background: radial-gradient(circle, #00cfe8 0%, transparent 70%);
    inset-block-end: -60px; inset-inline-start: 10%;
    animation-delay: -3s;
}
.dash-welcome__orb--3 {
    width: 150px; height: 150px;
    background: radial-gradient(circle, #28c76f 0%, transparent 70%);
    inset-block-start: 20%; inset-inline-end: 30%;
    animation-delay: -5s; opacity: 0.25;
}
@keyframes dash-orb-drift {
    from { transform: translateY(0)    scale(1);    }
    to   { transform: translateY(20px) scale(1.08); }
}
.dash-welcome::before {
    content: '';
    position: absolute; inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.dash-welcome__content { position: relative; z-index: 1; }
.dash-welcome__greeting {
    font-size: 0.8rem; font-weight: 600; letter-spacing: 0.12em;
    text-transform: uppercase; color: rgba(255,255,255,0.55); margin-bottom: 0.4rem;
}
.dash-welcome__name {
    font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 800; line-height: 1.25; margin-bottom: 0.5rem;
    background: linear-gradient(90deg, #fff 0%, rgba(255,255,255,0.75) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.dash-welcome__subtitle { font-size: 0.9rem; color: rgba(255,255,255,0.6); max-width: 30rem; line-height: 1.6; }
.dash-welcome__chips { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 1.5rem; }
.dash-welcome__chip {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.35rem 0.9rem; border-radius: 50rem; font-size: 0.78rem; font-weight: 600;
    backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.10); color: #fff; white-space: nowrap;
}
.dash-welcome__chip i { font-size: 0.9rem; opacity: 0.85; }
.dash-welcome__date {
    position: absolute; inset-block-start: 1.5rem; inset-inline-end: 1.75rem;
    z-index: 1; text-align: end; color: rgba(255,255,255,0.5); font-size: 0.78rem; line-height: 1.5;
}
.dash-welcome__date-day { font-size: 2.2rem; font-weight: 800; color: rgba(255,255,255,0.18); line-height: 1; display: block; }

/* ═══════════════════════════════════════════════════════════
   SECTION LABEL
═══════════════════════════════════════════════════════════ */
.dash-section-label {
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.1em; opacity: 0.4; margin-bottom: 0.75rem;
}

/* ═══════════════════════════════════════════════════════════
   GLASS STAT CARDS  — new design matching screenshot
═══════════════════════════════════════════════════════════ */
.dsc {
    position: relative;
    display: flex; flex-direction: column;
    border-radius: 0.875rem;
    padding: 0.75rem 0.9rem 0.7rem;
    overflow: hidden;
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    background: rgba(30, 28, 50, 0.72);
    border: 1px solid rgba(var(--dsc-rgb), 0.18);
    box-shadow: 0 1px 0 rgba(255,255,255,0.05) inset,
                0 2px 8px rgba(0,0,0,0.22);
    transition: transform 0.22s ease, box-shadow 0.22s ease;
    cursor: default;
    animation: dsc-in 0.45s ease-out both;
}
.dsc:hover {
    transform: translateY(-4px);
    box-shadow:
        0 1px 0   rgba(255,255,255,0.07) inset,
        0 6px 16px rgba(0,0,0,0.30),
        0 16px 36px rgba(var(--dsc-rgb), 0.28);
}
@keyframes dsc-in {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0);    }
}
/* subtle corner gradient */
.dsc::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 60% at 100% 0%, rgba(var(--dsc-rgb),0.14) 0%, transparent 60%);
    pointer-events: none;
}
/* shimmer sweep */
.dsc::after {
    content: '';
    position: absolute;
    top: -50%; inset-inline-start: -80%;
    width: 45%; height: 200%;
    background: linear-gradient(105deg, transparent 38%, rgba(255,255,255,0.055) 50%, transparent 62%);
    transform: skewX(-18deg);
    transition: inset-inline-start 0.5s ease;
    pointer-events: none;
}
.dsc:hover::after { inset-inline-start: 145%; }

/* ── Color tokens ── */
.dsc--purple  { --dsc-rgb: 115,103,240; --dsc-hex: #7367f0; }
.dsc--green   { --dsc-rgb: 40,199,111;  --dsc-hex: #28c76f; }
.dsc--cyan    { --dsc-rgb: 0,207,232;   --dsc-hex: #00cfe8; }
.dsc--red     { --dsc-rgb: 234,84,85;   --dsc-hex: #ea5455; }
.dsc--orange  { --dsc-rgb: 255,159,67;  --dsc-hex: #ff9f43; }
.dsc--blue    { --dsc-rgb: 30,136,229;  --dsc-hex: #1e88e5; }
.dsc--pink    { --dsc-rgb: 232,97,216;  --dsc-hex: #e861d8; }
.dsc--teal    { --dsc-rgb: 32,201,151;  --dsc-hex: #20c997; }

/* ── Stagger ── */
.dsc:nth-child(1) { animation-delay: 0ms;   }
.dsc:nth-child(2) { animation-delay: 60ms;  }
.dsc:nth-child(3) { animation-delay: 120ms; }
.dsc:nth-child(4) { animation-delay: 180ms; }

/* ── TOP ROW ── */
.dsc__top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 0.65rem;
}
.dsc__icon {
    width: 32px; height: 32px;
    border-radius: 0.5rem;
    background: rgba(var(--dsc-rgb), 0.18);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem;
    color: rgb(var(--dsc-rgb));
    border: 1px solid rgba(var(--dsc-rgb), 0.25);
    flex-shrink: 0;
}
.dsc__change {
    display: inline-flex; align-items: center; gap: 0.2rem;
    padding: 0.15rem 0.4rem; border-radius: 0.35rem;
    font-size: 0.65rem; font-weight: 700; white-space: nowrap;
}
.dsc__change--up   { background: rgba(40,199,111,0.18);  color: #28c76f; }
.dsc__change--down { background: rgba(234,84,85,0.18);   color: #ea5455; }
.dsc__change i { font-size: 0.7rem; }

/* ── BODY ── */
.dsc__body { flex: 1; }
.dsc__label {
    font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.07em;
    opacity: 0.48; font-weight: 600; margin-bottom: 0.1rem;
}
.dsc__value {
    font-size: 1.45rem; font-weight: 800; line-height: 1.1;
    color: #fff; letter-spacing: -0.02em;
}
.dsc__sub {
    font-size: 0.68rem; opacity: 0.42; margin-top: 0.1rem;
}

/* ── FOOTER ── */
.dsc__foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-top: 0.65rem;
    padding-top: 0.6rem;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.dsc__bar-wrap {
    flex: 1;
    height: 4px;
    border-radius: 99px;
    background: rgba(255,255,255,0.07);
    overflow: hidden;
}
.dsc__bar-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, rgba(var(--dsc-rgb),0.4) 0%, rgb(var(--dsc-rgb)) 100%);
    transition: width 1s cubic-bezier(0.4,0,0.2,1);
    width: 0; /* animated via JS */
}
.dsc__link {
    font-size: 0.72rem;
    font-weight: 600;
    color: rgb(var(--dsc-rgb));
    text-decoration: none;
    white-space: nowrap;
    opacity: 0.85;
    transition: opacity 0.18s;
    flex-shrink: 0;
}
.dsc__link:hover { opacity: 1; color: rgb(var(--dsc-rgb)); }

/* ── Light mode ── */
[data-theme="light"] .dsc {
    background: rgba(255,255,255,0.82);
    border-color: rgba(var(--dsc-rgb), 0.18);
    box-shadow: 0 1px 0 rgba(255,255,255,0.9) inset,
                0 2px 8px rgba(0,0,0,0.07);
}
[data-theme="light"] .dsc:hover {
    box-shadow:
        0 1px 0   rgba(255,255,255,0.9) inset,
        0 6px 16px rgba(0,0,0,0.09),
        0 16px 36px rgba(var(--dsc-rgb), 0.24);
}
[data-theme="light"] .dsc__value { color: #32304d; }
[data-theme="light"] .dsc__bar-wrap { background: rgba(0,0,0,0.07); }
[data-theme="light"] .dsc__foot { border-top-color: rgba(0,0,0,0.06); }

/* ═══════════════════════════════════════════════════════════
   CHART CARDS
═══════════════════════════════════════════════════════════ */
.dash-chart {
    border-radius: 1rem;
    backdrop-filter: blur(18px) saturate(170%);
    -webkit-backdrop-filter: blur(18px) saturate(170%);
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 4px 20px rgba(0,0,0,0.14);
    padding: 1.4rem 1.5rem 1rem;
}
.dash-chart__title { font-size: 0.9rem; font-weight: 700; margin-bottom: 0.2rem; opacity: 0.9; }
.dash-chart__sub   { font-size: 0.73rem; opacity: 0.4; margin-bottom: 1rem; }
.dash-chart__legend { display: flex; flex-wrap: wrap; gap: 0.9rem; margin-top: 0.75rem; }
.dash-chart__legend-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; opacity: 0.6; }
.dash-chart__dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }

[data-theme="light"] .dash-chart {
    background: rgba(255,255,255,0.72);
    border-color: rgba(115,103,240,0.10);
    box-shadow: 0 4px 18px rgba(115,103,240,0.08);
}

@media (prefers-reduced-motion: reduce) {
    .dsc, .dash-welcome__orb { animation: none !important; opacity: 1 !important; transform: none !important; }
    .dsc__bar-fill { transition: none !important; }
}
</style>
@endpush

@section('content')
@php
    $admin = auth('admin')->user();
@endphp

{{-- ═══════════════ WELCOME BANNER ═══════════════ --}}
<div class="dash-welcome mb-4">
    <div class="dash-welcome__orb dash-welcome__orb--1"></div>
    <div class="dash-welcome__orb dash-welcome__orb--2"></div>
    <div class="dash-welcome__orb dash-welcome__orb--3"></div>
    <div class="dash-welcome__date">
        <span class="dash-welcome__date-day">{{ now()->format('d') }}</span>
        <span>{{ now()->format('M Y') }}</span>
    </div>
    <div class="dash-welcome__content">
        <div class="dash-welcome__greeting">{{ __('admin/main.home_greeting') }}</div>
        <div class="dash-welcome__name">{{ $admin->name }}</div>
        <p class="dash-welcome__subtitle">{{ __('admin/main.home_welcome_subtitle') }}</p>
        <div class="dash-welcome__chips">
            <span class="dash-welcome__chip">
                <i class="ti ti-users"></i>
                {{ number_format($stats['total_users']) }} {{ __('admin/main.home_stat_total_users') }}
            </span>
            <span class="dash-welcome__chip">
                <i class="ti ti-calendar-stats"></i>
                {{ number_format($stats['users_this_year']) }} {{ __('admin/main.home_stat_users_this_year') }}
            </span>
            <span class="dash-welcome__chip">
                <i class="ti ti-message-circle"></i>
                {{ number_format($stats['pending_complaints']) }} {{ __('admin/main.home_stat_pending_complaints') }}
            </span>
        </div>
    </div>
</div>

{{-- ═══════════════ USER STATS ═══════════════ --}}
<div class="dash-section-label">{{ __('admin/main.home_section_users') }}</div>
<div class="row g-3 mb-4">

    {{-- Total Users --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--purple h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-users"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    {{ $stats['users_this_year'] }}
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_all_time') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Users --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--green h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-user-check"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    {{ $stats['ratio_users'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_active_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['active_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_of_total') }} {{ $stats['total_users'] }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- New This Month --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--cyan h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-user-plus"></i></div>
                @php $chNew = $stats['change_new_users']; @endphp
                <span class="dsc__change {{ $chNew['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chNew['up'] ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                    {{ $chNew['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_new_this_month') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['new_this_month'] }}">0</div>
                <div class="dsc__sub">{{ now()->translatedFormat('F Y') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['total_users'] > 0 ? min(round($stats['new_this_month'] / $stats['total_users'] * 100 * 5), 100) : 0 }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Blocked Users --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--red h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-user-x"></i></div>
                <span class="dsc__change dsc__change--down">
                    <i class="ti ti-trending-down"></i>
                    {{ $stats['total_users'] > 0 ? round($stats['blocked_users'] / $stats['total_users'] * 100, 1) : 0 }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_blocked_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['blocked_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_restricted') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['total_users'] > 0 ? round($stats['blocked_users'] / $stats['total_users'] * 100) : 0 }}"></div>
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
        <div class="dsc dsc--orange h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-alert-circle"></i></div>
                @php $chCompl = $stats['change_complaints']; @endphp
                <span class="dsc__change {{ $chCompl['up'] ? 'dsc__change--down' : 'dsc__change--up' }}">
                    <i class="ti {{ $chCompl['up'] ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                    {{ $chCompl['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_complaints') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_complaints'] }}">0</div>
                <div class="dsc__sub">{{ $stats['pending_complaints'] }} {{ __('admin/main.home_stat_pending') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="#" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_complaints'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Messages --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--blue h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-mail"></i></div>
                @php $chCont = $stats['change_contacts']; @endphp
                <span class="dsc__change {{ $chCont['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chCont['up'] ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                    {{ $chCont['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_contacts') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_contacts'] }}">0</div>
                <div class="dsc__sub">+{{ $stats['new_contacts_month'] }} {{ __('admin/main.home_stat_this_month') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="#" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_contacts'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--pink h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-tag"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    {{ $stats['ratio_categories'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_categories') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_categories'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_categories'] }} {{ __('admin/main.home_stat_active') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="#" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_categories'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- FAQs / Posts / Sliders --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--teal h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-help-circle"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    {{ $stats['ratio_sliders'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_faqs') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_faqs'] }}">0</div>
                <div class="dsc__sub">
                    {{ $stats['total_posts'] }} {{ __('admin/main.home_stat_posts') }}
                    &nbsp;·&nbsp;
                    {{ $stats['total_sliders'] }} {{ __('admin/main.home_stat_sliders') }}
                </div>
            </div>
            <div class="dsc__foot">
                <a href="#" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_sliders'] }}"></div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════ CHARTS ROW 1 ═══════════════ --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-xl-8">
        <div class="dash-chart h-100">
            <div class="dash-chart__title">{{ __('admin/main.home_chart_monthly_title') }}</div>
            <div class="dash-chart__sub">{{ __('admin/main.home_chart_monthly_sub') }}</div>
            <div id="chartMonthly"></div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="dash-chart h-100">
            <div class="dash-chart__title">{{ __('admin/main.home_chart_status_title') }}</div>
            <div class="dash-chart__sub">{{ __('admin/main.home_chart_status_sub') }}</div>
            <div id="chartStatus"></div>
            <div class="dash-chart__legend justify-content-center">
                <div class="dash-chart__legend-item">
                    <div class="dash-chart__dot" style="background:#28c76f"></div>
                    {{ __('admin/main.home_stat_active_users') }}
                </div>
                <div class="dash-chart__legend-item">
                    <div class="dash-chart__dot" style="background:#ea5455"></div>
                    {{ __('admin/main.home_stat_blocked_users') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ CHARTS ROW 2 ═══════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="dash-chart h-100">
            <div class="dash-chart__title">{{ __('admin/main.home_chart_activity_title') }}</div>
            <div class="dash-chart__sub">{{ __('admin/main.home_chart_activity_sub') }}</div>
            <div id="chartActivity"></div>
            <div class="dash-chart__legend">
                <div class="dash-chart__legend-item">
                    <div class="dash-chart__dot" style="background:#ff9f43"></div>
                    {{ __('admin/main.home_stat_total_complaints') }}
                </div>
                <div class="dash-chart__legend-item">
                    <div class="dash-chart__dot" style="background:#1e88e5"></div>
                    {{ __('admin/main.home_stat_total_contacts') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="dash-chart h-100">
            <div class="dash-chart__title">{{ __('admin/main.home_chart_radial_title') }}</div>
            <div class="dash-chart__sub">{{ __('admin/main.home_chart_radial_sub') }}</div>
            <div id="chartRadial"></div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js"></script>
<script>
(function () {
    'use strict';

    /* ─── Theme helpers ─────────────────────────────────── */
    function isDark() {
        var t = document.documentElement.getAttribute('data-theme') || '';
        return t === 'dark' || (t !== 'light' && window.matchMedia('(prefers-color-scheme:dark)').matches);
    }
    var tc   = function () { return isDark() ? 'rgba(225,222,245,0.5)'  : 'rgba(50,48,77,0.5)';  };
    var gc   = function () { return isDark() ? 'rgba(255,255,255,0.05)' : 'rgba(50,48,77,0.05)'; };
    var mode = function () { return isDark() ? 'dark' : 'light'; };

    /* ─── 1. Area — Monthly Registrations ──────────────── */
    var areaChart = new ApexCharts(document.querySelector('#chartMonthly'), {
        chart: { type:'area', height:240, toolbar:{show:false}, background:'transparent',
                 animations:{enabled:true, easing:'easeinout', speed:750} },
        series: [{ name:'{{ __('admin/main.home_chart_monthly_series') }}', data:@json($stats['monthly_counts']) }],
        xaxis: { categories:@json($stats['monthly_labels']),
                 labels:{style:{colors:tc(),fontSize:'0.74rem'}}, axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis: { labels:{style:{colors:tc(),fontSize:'0.74rem'}, formatter:function(v){return Math.round(v);}}, min:0 },
        grid: { borderColor:gc(), strokeDashArray:4, xaxis:{lines:{show:false}} },
        stroke: { curve:'smooth', width:2.5 },
        colors: ['#7367f0'],
        fill: { type:'gradient', gradient:{shadeIntensity:1, opacityFrom:0.35, opacityTo:0.02, stops:[0,95,100]} },
        markers: { size:4, colors:['#7367f0'], strokeColors:isDark()?'#2f2b3d':'#fff', strokeWidth:2 },
        dataLabels: { enabled:false },
        tooltip: { theme:mode(), style:{fontSize:'0.78rem'} },
        theme: { mode:mode() },
    });
    areaChart.render();

    /* ─── 2. Donut — User Status ────────────────────────── */
    var donutChart = new ApexCharts(document.querySelector('#chartStatus'), {
        chart: { type:'donut', height:220, background:'transparent',
                 animations:{enabled:true, easing:'easeinout', speed:750} },
        series: [{{ (int)$stats['active_users'] }}, {{ (int)$stats['blocked_users'] }}],
        labels: ['{{ __('admin/main.home_stat_active_users') }}','{{ __('admin/main.home_stat_blocked_users') }}'],
        colors: ['#28c76f','#ea5455'],
        stroke: { width:0 },
        plotOptions: { pie: { donut: { size:'68%', labels: { show:true,
            total:{ show:true, label:'{{ __('admin/main.home_chart_total') }}', color:tc(), fontSize:'0.76rem',
                    formatter:function(w){ return w.globals.seriesTotals.reduce(function(a,b){return a+b;},0); } },
            value:{ fontSize:'1.5rem', fontWeight:800, color:isDark()?'#e1def5':'#32304d' } } } } },
        dataLabels: { enabled:false },
        legend: { show:false },
        tooltip: { theme:mode(), style:{fontSize:'0.78rem'} },
        theme: { mode:mode() },
    });
    donutChart.render();

    /* ─── 3. Grouped Bar — Monthly Activity ─────────────── */
    var barChart = new ApexCharts(document.querySelector('#chartActivity'), {
        chart: { type:'bar', height:240, toolbar:{show:false}, background:'transparent',
                 animations:{enabled:true, easing:'easeinout', speed:750} },
        series: [
            { name:'{{ __('admin/main.home_stat_total_complaints') }}', data:@json($stats['activity_complaints']) },
            { name:'{{ __('admin/main.home_stat_total_contacts') }}',   data:@json($stats['activity_contacts'])   },
        ],
        xaxis: { categories:@json($stats['activity_labels']),
                 labels:{style:{colors:tc(),fontSize:'0.74rem'}}, axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis: { labels:{style:{colors:tc(),fontSize:'0.74rem'}, formatter:function(v){return Math.round(v);}}, min:0 },
        grid: { borderColor:gc(), strokeDashArray:4, xaxis:{lines:{show:false}} },
        colors: ['#ff9f43','#1e88e5'],
        plotOptions: { bar:{ columnWidth:'50%', borderRadius:5, borderRadiusApplication:'end' } },
        dataLabels: { enabled:false },
        legend: { show:false },
        tooltip: { theme:mode(), style:{fontSize:'0.78rem'}, shared:true, intersect:false },
        theme: { mode:mode() },
    });
    barChart.render();

    /* ─── 4. Radial Bar — Active Ratios ─────────────────── */
    var radialChart = new ApexCharts(document.querySelector('#chartRadial'), {
        chart: { type:'radialBar', height:260, background:'transparent',
                 animations:{enabled:true, easing:'easeinout', speed:900} },
        series: [{{ $stats['ratio_users'] }}, {{ $stats['ratio_categories'] }}, {{ $stats['ratio_sliders'] }}],
        labels: ['{{ __('admin/main.home_stat_active_users') }}','{{ __('admin/main.home_stat_total_categories') }}','{{ __('admin/main.home_stat_sliders') }}'],
        colors: ['#28c76f','#e861d8','#00cfe8'],
        plotOptions: { radialBar: {
            hollow:{ size:'20%', margin:5 },
            track:{ background:isDark()?'rgba(255,255,255,0.05)':'rgba(0,0,0,0.05)', strokeWidth:'100%' },
            dataLabels: {
                name:{ fontSize:'0.72rem', offsetY:-4, color:tc() },
                value:{ fontSize:'0.9rem', fontWeight:700, color:isDark()?'#e1def5':'#32304d',
                        formatter:function(v){ return v+'%'; } },
                total:{ show:true, label:'{{ __('admin/main.home_chart_total') }}', color:tc(), fontSize:'0.72rem',
                        formatter:function(){ return '{{ $stats['ratio_users'] }}%'; } },
            },
            startAngle:-135, endAngle:135,
        }},
        stroke: { lineCap:'round' },
        tooltip: { enabled:false },
        theme: { mode:mode() },
    });
    radialChart.render();

    /* ─── Re-render on theme toggle ─────────────────────── */
    document.querySelectorAll('[data-theme]').forEach(function(el) {
        el.addEventListener('click', function() {
            setTimeout(function() {
                var m = mode();
                var ax = {labels:{style:{colors:tc()}}};
                var opts = {theme:{mode:m}, tooltip:{theme:m}, grid:{borderColor:gc()}, xaxis:ax, yaxis:{labels:{style:{colors:tc()}}}};
                [areaChart, barChart].forEach(function(c){ c.updateOptions(opts); });
                donutChart.updateOptions({ theme:{mode:m}, tooltip:{theme:m},
                    plotOptions:{pie:{donut:{labels:{total:{color:tc()}, value:{color:isDark()?'#e1def5':'#32304d'}}}}} });
                radialChart.updateOptions({ theme:{mode:m},
                    plotOptions:{radialBar:{track:{background:isDark()?'rgba(255,255,255,0.05)':'rgba(0,0,0,0.05)'},
                        dataLabels:{name:{color:tc()}, value:{color:isDark()?'#e1def5':'#32304d'}, total:{color:tc()}}}} });
            }, 50);
        });
    });

    /* ─── Animated counters ─────────────────────────────── */
    document.querySelectorAll('[data-counter]').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        var start  = performance.now(), dur = 900;
        (function step(now) {
            var p = Math.min((now - start) / dur, 1);
            el.textContent = Math.round((1 - Math.pow(1-p, 3)) * target).toLocaleString();
            if (p < 1) requestAnimationFrame(step);
        })(start);
    });

    /* ─── Animated progress bars ────────────────────────── */
    setTimeout(function() {
        document.querySelectorAll('.dsc__bar-fill').forEach(function(el) {
            el.style.width = (el.getAttribute('data-width') || 0) + '%';
        });
    }, 300);

})();
</script>
@endpush
