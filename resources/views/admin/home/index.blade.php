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
    background: rgba(30,28,50,0.65);
    border: 1px solid rgba(255,255,255,0.07);
    box-shadow: 0 2px 8px rgba(0,0,0,0.22);
    padding: 0;
    overflow: hidden;
}
.dash-chart__head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem 0.75rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.dash-chart__head-left { display: flex; align-items: center; gap: 0.6rem; }
.dash-chart__icon {
    width: 32px; height: 32px; border-radius: 0.5rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem; flex-shrink: 0;
}
.dash-chart__title { font-size: 0.85rem; font-weight: 700; opacity: 0.9; margin: 0; }
.dash-chart__sub   { font-size: 0.7rem; opacity: 0.38; margin: 0; }
.dash-chart__badge {
    display: inline-flex; align-items: center; gap: 0.2rem;
    padding: 0.18rem 0.55rem; border-radius: 50rem;
    font-size: 0.68rem; font-weight: 700;
}
.dash-chart__body  { padding: 0.5rem 0.5rem 0.75rem; }
.dash-chart__legend { display: flex; flex-wrap: wrap; gap: 0.75rem; padding: 0 1.25rem 1rem; }
.dash-chart__legend-item { display: flex; align-items: center; gap: 0.38rem; font-size: 0.72rem; opacity: 0.55; }
.dash-chart__dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

[data-theme="light"] .dash-chart {
    background: rgba(255,255,255,0.80);
    border-color: rgba(115,103,240,0.08);
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
}
[data-theme="light"] .dash-chart__head { border-bottom-color: rgba(0,0,0,0.05); }

@media (prefers-reduced-motion: reduce) {
    .dsc, .dash-welcome__orb { animation: none !important; opacity: 1 !important; transform: none !important; }
    .dsc__bar-fill { transition: none !important; }
}

/* ═══════════════════════════════════════════════════════════
   QUICK-ACTION TABLES
═══════════════════════════════════════════════════════════ */
.dqt {
    border-radius: 1rem;
    backdrop-filter: blur(18px) saturate(170%);
    -webkit-backdrop-filter: blur(18px) saturate(170%);
    background: rgba(30,28,50,0.65);
    border: 1px solid rgba(255,255,255,0.07);
    box-shadow: 0 2px 8px rgba(0,0,0,0.22);
    overflow: hidden;
}
[data-theme="light"] .dqt {
    background: rgba(255,255,255,0.80);
    border-color: rgba(115,103,240,0.08);
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}
/* header */
.dqt__head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.85rem 1.1rem 0.7rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
[data-theme="light"] .dqt__head { border-bottom-color: rgba(0,0,0,0.05); }

.dqt__head-left { display: flex; align-items: center; gap: 0.55rem; }
.dqt__icon {
    width: 30px; height: 30px; border-radius: 0.45rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}
.dqt__title { font-size: 0.82rem; font-weight: 700; opacity: 0.88; }
.dqt__view-all {
    font-size: 0.7rem; font-weight: 600;
    color: #7367f0; text-decoration: none; opacity: 0.75;
    transition: opacity .18s;
    white-space: nowrap;
}
.dqt__view-all:hover { opacity: 1; color: #7367f0; }

/* table itself */
.dqt__table { width: 100%; border-collapse: collapse; }
.dqt__table th {
    font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.08em;
    font-weight: 700; opacity: 0.38; padding: 0.55rem 1.1rem;
    text-align: start; white-space: nowrap;
}
.dqt__table td {
    padding: 0.55rem 1.1rem; font-size: 0.78rem;
    border-top: 1px solid rgba(255,255,255,0.04);
    vertical-align: middle; white-space: nowrap;
}
[data-theme="light"] .dqt__table td { border-top-color: rgba(0,0,0,0.04); }

.dqt__table tbody tr {
    transition: background .18s;
}
.dqt__table tbody tr:hover { background: rgba(115,103,240,0.06); }

/* avatar */
.dqt__avatar {
    width: 30px; height: 30px; border-radius: 50%;
    object-fit: cover; flex-shrink: 0;
}
.dqt__avatar-fallback {
    width: 30px; height: 30px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700; color: #fff; flex-shrink: 0;
    background: linear-gradient(135deg, #7367f0, #9e95f5);
}
.dqt__name { font-weight: 600; font-size: 0.78rem; max-width: 130px; overflow: hidden; text-overflow: ellipsis; }
.dqt__sub  { font-size: 0.68rem; opacity: 0.42; margin-top: 1px; }
.dqt__date { font-size: 0.68rem; opacity: 0.38; }

/* status badges */
.dqt__badge {
    display: inline-flex; align-items: center; gap: 0.22rem;
    padding: 0.15rem 0.5rem; border-radius: 50rem;
    font-size: 0.63rem; font-weight: 700; white-space: nowrap;
}
.dqt__badge--active  { background: rgba(40,199,111,.14); color: #28c76f; }
.dqt__badge--blocked { background: rgba(234,84,85,.14);  color: #ea5455; }
.dqt__badge--pending { background: rgba(255,159,67,.14); color: #ff9f43; }
.dqt__badge--done    { background: rgba(40,199,111,.14); color: #28c76f; }
.dqt__badge--process { background: rgba(0,207,232,.14);  color: #00cfe8; }
.dqt__badge--reject  { background: rgba(234,84,85,.14);  color: #ea5455; }

/* action icon */
.dqt__action {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; border-radius: 0.4rem;
    background: rgba(115,103,240,.12); color: #7367f0;
    font-size: 0.8rem; text-decoration: none;
    transition: background .18s, transform .18s;
}
.dqt__action:hover { background: rgba(115,103,240,.22); color: #7367f0; transform: scale(1.1); }

/* empty state */
.dqt__empty {
    text-align: center; padding: 1.8rem 1rem;
    font-size: 0.78rem; opacity: 0.32;
}
.dqt__empty i { font-size: 1.6rem; display: block; margin-bottom: 0.4rem; opacity: .5; }
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

    {{-- Users This Year --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--orange h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-calendar-stats"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    {{ now()->format('Y') }}
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_users_this_year') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['users_this_year'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_this_year') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="{{ $stats['total_users'] > 0 ? min(round($stats['users_this_year'] / $stats['total_users'] * 100), 100) : 0 }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Admins --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--blue h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-shield-check"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    100%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_admins') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_admins'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_admins_sub') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.admins.index') }}" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
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

    {{-- FAQs --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--teal h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-help-circle"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    100%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_faqs') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_faqs'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_faqs_sub') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="#" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Posts --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--purple h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-article"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    100%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_posts') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_posts'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_posts_sub') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="#" class="dsc__link">{{ __('admin/main.home_card_view') }} ←</a>
                <div class="dsc__bar-wrap">
                    <div class="dsc__bar-fill" data-width="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sliders --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--green h-100">
            <div class="dsc__top">
                <div class="dsc__icon"><i class="ti ti-slideshow"></i></div>
                <span class="dsc__change dsc__change--up">
                    <i class="ti ti-trending-up"></i>
                    {{ $stats['ratio_sliders'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_sliders') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_sliders'] }}">0</div>
                <div class="dsc__sub">{{ $stats['active_sliders'] }} {{ __('admin/main.home_stat_active') }}</div>
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

    {{-- Multi-series Area: Users + Admins monthly --}}
    <div class="col-12 col-xl-8">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon" style="background:rgba(115,103,240,0.15);color:#7367f0">
                        <i class="ti ti-chart-line"></i>
                    </div>
                    <div>
                        <div class="dash-chart__title">{{ __('admin/main.home_chart_monthly_title') }}</div>
                        <div class="dash-chart__sub">{{ __('admin/main.home_chart_monthly_sub') }}</div>
                    </div>
                </div>
                <span class="dash-chart__badge" style="background:rgba(115,103,240,0.12);color:#7367f0">
                    {{ __('admin/main.home_chart_last_6') }}
                </span>
            </div>
            <div class="dash-chart__body"><div id="chartMonthly"></div></div>
            <div class="dash-chart__legend">
                <div class="dash-chart__legend-item">
                    <div class="dash-chart__dot" style="background:#7367f0"></div>
                    {{ __('admin/main.home_stat_total_users') }}
                </div>
                <div class="dash-chart__legend-item">
                    <div class="dash-chart__dot" style="background:#1e88e5"></div>
                    {{ __('admin/main.home_stat_total_admins') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Donut: Full platform distribution --}}
    <div class="col-12 col-xl-4">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon" style="background:rgba(0,207,232,0.15);color:#00cfe8">
                        <i class="ti ti-chart-donut"></i>
                    </div>
                    <div>
                        <div class="dash-chart__title">{{ __('admin/main.home_chart_dist_title') }}</div>
                        <div class="dash-chart__sub">{{ __('admin/main.home_chart_dist_sub') }}</div>
                    </div>
                </div>
            </div>
            <div class="dash-chart__body"><div id="chartDist"></div></div>
            <div class="dash-chart__legend" style="justify-content:center">
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#7367f0"></div>{{ __('admin/main.home_stat_total_users') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#ff9f43"></div>{{ __('admin/main.home_stat_total_complaints') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#1e88e5"></div>{{ __('admin/main.home_stat_total_contacts') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#e861d8"></div>{{ __('admin/main.home_stat_total_categories') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#20c997"></div>{{ __('admin/main.home_stat_total_faqs') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#ea5455"></div>{{ __('admin/main.home_stat_posts') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#28c76f"></div>{{ __('admin/main.home_stat_sliders') }}</div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════ CHARTS ROW 2 ═══════════════ --}}
<div class="row g-3 mb-4">

    {{-- Mixed: Bars (complaints+contacts) + Line (users) --}}
    <div class="col-12 col-xl-7">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon" style="background:rgba(255,159,67,0.15);color:#ff9f43">
                        <i class="ti ti-chart-bar"></i>
                    </div>
                    <div>
                        <div class="dash-chart__title">{{ __('admin/main.home_chart_activity_title') }}</div>
                        <div class="dash-chart__sub">{{ __('admin/main.home_chart_activity_sub') }}</div>
                    </div>
                </div>
                <span class="dash-chart__badge" style="background:rgba(255,159,67,0.12);color:#ff9f43">
                    {{ __('admin/main.home_chart_last_6') }}
                </span>
            </div>
            <div class="dash-chart__body"><div id="chartMixed"></div></div>
            <div class="dash-chart__legend">
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#ff9f43"></div>{{ __('admin/main.home_stat_total_complaints') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#1e88e5"></div>{{ __('admin/main.home_stat_total_contacts') }}</div>
                <div class="dash-chart__legend-item"><div class="dash-chart__dot" style="background:#7367f0"></div>{{ __('admin/main.home_stat_total_users') }}</div>
            </div>
        </div>
    </div>

    {{-- Polar Area: Active ratios per model --}}
    <div class="col-12 col-xl-5">
        <div class="dash-chart h-100">
            <div class="dash-chart__head">
                <div class="dash-chart__head-left">
                    <div class="dash-chart__icon" style="background:rgba(40,199,111,0.15);color:#28c76f">
                        <i class="ti ti-chart-radar"></i>
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
                    <div class="dqt__icon" style="background:rgba(115,103,240,.14);color:#7367f0">
                        <i class="ti ti-users"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_latest_users') }}</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="dqt__view-all">
                    {{ __('admin/main.home_table_view_all') }} ←
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
                                    <img src="{{ asset('storage/'.$u->image) }}" class="dqt__avatar" alt="">
                                @else
                                    <span class="dqt__avatar-fallback">{{ mb_substr($u->name, 0, 1) }}</span>
                                @endif
                                <div>
                                    <div class="dqt__name">{{ $u->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="dqt__sub">{{ $u->phone ?? '—' }}</span></td>
                        <td>
                            @if($u->is_blocked)
                                <span class="dqt__badge dqt__badge--blocked">
                                    <i class="ti ti-lock"></i> {{ __('admin/main.home_badge_blocked') }}
                                </span>
                            @else
                                <span class="dqt__badge dqt__badge--active">
                                    <i class="ti ti-circle-check"></i> {{ __('admin/main.home_badge_active') }}
                                </span>
                            @endif
                        </td>
                        <td><span class="dqt__date">{{ $u->created_at->diffForHumans() }}</span></td>
                        <td>
                            <a href="{{ route('admin.users.index') }}" class="dqt__action">
                                <i class="ti ti-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="dqt__empty"><i class="ti ti-users"></i>{{ __('admin/main.home_table_empty') }}</div></td></tr>
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
                    <div class="dqt__icon" style="background:rgba(255,159,67,.14);color:#ff9f43">
                        <i class="ti ti-alert-circle"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_pending_complaints') }}</span>
                </div>
                <a href="#" class="dqt__view-all">{{ __('admin/main.home_table_view_all') }} ←</a>
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
                            'pending'    => ['class'=>'dqt__badge--pending', 'icon'=>'ti-clock',         'label'=>__('admin/main.home_badge_pending')],
                            'processing' => ['class'=>'dqt__badge--process', 'icon'=>'ti-refresh',       'label'=>__('admin/main.home_badge_processing')],
                            'completed'  => ['class'=>'dqt__badge--done',    'icon'=>'ti-circle-check',  'label'=>__('admin/main.home_badge_completed')],
                            'rejected'   => ['class'=>'dqt__badge--reject',  'icon'=>'ti-x',             'label'=>__('admin/main.home_badge_rejected')],
                        ];
                        $st = $statusMap[$c->status] ?? $statusMap['pending'];
                    @endphp
                    <tr>
                        <td>
                            <div class="dqt__name">{{ $c->name }}</div>
                            <div class="dqt__sub">{{ $c->phone ?? $c->email ?? '—' }}</div>
                        </td>
                        <td><span style="max-width:120px;display:block;overflow:hidden;text-overflow:ellipsis;font-size:.75rem">{{ $c->subject }}</span></td>
                        <td>
                            <span class="dqt__badge {{ $st['class'] }}">
                                <i class="ti {{ $st['icon'] }}"></i> {{ $st['label'] }}
                            </span>
                        </td>
                        <td><span class="dqt__date">{{ \Carbon\Carbon::parse($c->created_at)->diffForHumans() }}</span></td>
                        <td>
                            <a href="#" class="dqt__action"><i class="ti ti-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="dqt__empty"><i class="ti ti-circle-check"></i>{{ __('admin/main.home_table_no_pending') }}</div></td></tr>
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
                    <div class="dqt__icon" style="background:rgba(30,136,229,.14);color:#1e88e5">
                        <i class="ti ti-mail"></i>
                    </div>
                    <span class="dqt__title">{{ __('admin/main.home_table_latest_contacts') }}</span>
                </div>
                <a href="#" class="dqt__view-all">{{ __('admin/main.home_table_view_all') }} ←</a>
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
                                <span class="dqt__avatar-fallback" style="background:linear-gradient(135deg,#1e88e5,#64b5f6)">
                                    {{ mb_substr($m->name, 0, 1) }}
                                </span>
                                <span class="dqt__name">{{ $m->name }}</span>
                            </div>
                        </td>
                        <td><span class="dqt__sub">{{ $m->email ?? '—' }}</span></td>
                        <td><span class="dqt__sub">{{ $m->phone ?? '—' }}</span></td>
                        <td><span style="max-width:200px;display:block;overflow:hidden;text-overflow:ellipsis;font-size:.75rem">{{ $m->subject }}</span></td>
                        <td><span class="dqt__date">{{ \Carbon\Carbon::parse($m->created_at)->diffForHumans() }}</span></td>
                        <td>
                            <a href="#" class="dqt__action"><i class="ti ti-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="dqt__empty"><i class="ti ti-mail"></i>{{ __('admin/main.home_table_empty') }}</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js"></script>
<script>
(function () {
    'use strict';

    /* ─── Theme helpers ─────────────────────────────── */
    function isDark() {
        var t = document.documentElement.getAttribute('data-theme') || '';
        return t === 'dark' || (t !== 'light' && window.matchMedia('(prefers-color-scheme:dark)').matches);
    }
    var tc   = function () { return isDark() ? 'rgba(225,222,245,0.45)' : 'rgba(50,48,77,0.45)'; };
    var gc   = function () { return isDark() ? 'rgba(255,255,255,0.04)' : 'rgba(50,48,77,0.05)'; };
    var sc   = function () { return isDark() ? '#2a2742' : '#fff'; };
    var mode = function () { return isDark() ? 'dark' : 'light'; };

    var sharedAxis = function () {
        return {
            labels: { style: { colors: tc(), fontSize: '0.72rem', fontFamily: 'inherit' } },
            axisBorder: { show: false }, axisTicks: { show: false }
        };
    };
    var sharedGrid = function () {
        return { borderColor: gc(), strokeDashArray: 4, xaxis: { lines: { show: false } } };
    };
    var sharedTooltip = function () {
        return { theme: mode(), style: { fontSize: '0.78rem', fontFamily: 'inherit' } };
    };

    /* ── 1. Multi-series Area ── Users + Admins monthly ── */
    var areaChart = new ApexCharts(document.querySelector('#chartMonthly'), {
        chart: {
            type: 'area', height: 230,
            toolbar: { show: false }, background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 800,
                          animateGradually: { enabled: true, delay: 120 } },
        },
        series: [
            { name: '{{ __('admin/main.home_stat_total_users') }}',  data: @json($stats['monthly_counts']) },
            { name: '{{ __('admin/main.home_stat_total_admins') }}', data: @json($stats['admin_monthly_counts']) },
        ],
        xaxis: Object.assign({ categories: @json($stats['monthly_labels']) }, sharedAxis()),
        yaxis: { labels: { style: { colors: tc(), fontSize: '0.72rem' }, formatter: function(v){ return Math.round(v); } }, min: 0 },
        grid: sharedGrid(),
        stroke: { curve: 'smooth', width: [2.5, 2] },
        colors: ['#7367f0', '#1e88e5'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: [0.32, 0.22], opacityTo: [0.01, 0.01], stops: [0, 90, 100] }
        },
        markers: { size: [4, 3], strokeColors: sc(), strokeWidth: 2, hover: { sizeOffset: 2 } },
        dataLabels: { enabled: false },
        tooltip: Object.assign({ shared: true, intersect: false }, sharedTooltip()),
        theme: { mode: mode() },
    });
    areaChart.render();

    /* ── 2. Donut ── Full platform distribution ─────── */
    var distSeries = @json($stats['dist_series']);
    var distChart = new ApexCharts(document.querySelector('#chartDist'), {
        chart: {
            type: 'donut', height: 240, background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 800 },
        },
        series: distSeries,
        labels: [
            '{{ __('admin/main.home_stat_total_users') }}',
            '{{ __('admin/main.home_stat_total_complaints') }}',
            '{{ __('admin/main.home_stat_total_contacts') }}',
            '{{ __('admin/main.home_stat_total_categories') }}',
            '{{ __('admin/main.home_stat_total_faqs') }}',
            '{{ __('admin/main.home_stat_posts') }}',
            '{{ __('admin/main.home_stat_sliders') }}',
        ],
        colors: ['#7367f0','#ff9f43','#1e88e5','#e861d8','#20c997','#ea5455','#28c76f'],
        stroke: { width: 0 },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: '{{ __('admin/main.home_chart_total') }}',
                            color: tc(), fontSize: '0.72rem',
                            formatter: function(w) {
                                return w.globals.seriesTotals.reduce(function(a, b){ return a + b; }, 0);
                            }
                        },
                        value: { fontSize: '1.4rem', fontWeight: 800,
                                 color: isDark() ? '#e1def5' : '#32304d',
                                 offsetY: 4 },
                        name: { fontSize: '0.7rem', color: tc(), offsetY: -6 },
                    }
                },
                expandOnClick: true,
            }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: sharedTooltip(),
        theme: { mode: mode() },
    });
    distChart.render();

    /* ── 3. Mixed ── Bars (complaints+contacts) + Line (users) */
    var mixedChart = new ApexCharts(document.querySelector('#chartMixed'), {
        chart: {
            type: 'bar', height: 230,
            toolbar: { show: false }, background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 750 },
        },
        series: [
            { name: '{{ __('admin/main.home_stat_total_complaints') }}', type: 'column', data: @json($stats['activity_complaints']) },
            { name: '{{ __('admin/main.home_stat_total_contacts') }}',   type: 'column', data: @json($stats['activity_contacts']) },
            { name: '{{ __('admin/main.home_stat_total_users') }}',      type: 'line',   data: @json($stats['monthly_counts']) },
        ],
        xaxis: Object.assign({ categories: @json($stats['activity_labels']) }, sharedAxis()),
        yaxis: [
            { seriesName: '{{ __('admin/main.home_stat_total_complaints') }}',
              labels: { style: { colors: tc(), fontSize: '0.72rem' }, formatter: function(v){ return Math.round(v); } }, min: 0 },
            { seriesName: '{{ __('admin/main.home_stat_total_contacts') }}', show: false },
            { seriesName: '{{ __('admin/main.home_stat_total_users') }}',
              opposite: true,
              labels: { style: { colors: tc(), fontSize: '0.72rem' }, formatter: function(v){ return Math.round(v); } }, min: 0 },
        ],
        grid: sharedGrid(),
        colors: ['#ff9f43', '#1e88e5', '#7367f0'],
        plotOptions: { bar: { columnWidth: '45%', borderRadius: 4, borderRadiusApplication: 'end' } },
        stroke: { width: [0, 0, 2.5], curve: 'smooth' },
        fill: { opacity: [0.85, 0.85, 1] },
        markers: { size: [0, 0, 4], strokeColors: sc(), strokeWidth: 2 },
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: Object.assign({ shared: true, intersect: false }, sharedTooltip()),
        theme: { mode: mode() },
    });
    mixedChart.render();

    /* ── 4. Polar Area ── Active ratios per model ───── */
    var polarChart = new ApexCharts(document.querySelector('#chartPolar'), {
        chart: {
            type: 'polarArea', height: 270, background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 900 },
            toolbar: { show: false },
        },
        series: [
            {{ $stats['ratio_users'] }},
            {{ $stats['ratio_categories'] }},
            {{ $stats['ratio_sliders'] }},
            {{ $stats['ratio_complaints'] }},
        ],
        labels: [
            '{{ __('admin/main.home_stat_active_users') }}',
            '{{ __('admin/main.home_stat_total_categories') }}',
            '{{ __('admin/main.home_stat_sliders') }}',
            '{{ __('admin/main.home_chart_resolved') }}',
        ],
        colors: ['#7367f0', '#e861d8', '#28c76f', '#ff9f43'],
        stroke: { width: 0 },
        fill: { opacity: 0.75 },
        yaxis: { show: false },
        plotOptions: {
            polarArea: { rings: { strokeWidth: 1, strokeColor: gc() },
                         spokes: { strokeWidth: 1, connectorColors: gc() } }
        },
        dataLabels: {
            enabled: true,
            formatter: function(v) { return Math.round(v) + '%'; },
            style: { fontSize: '0.72rem', fontFamily: 'inherit', colors: ['#fff'] },
            dropShadow: { enabled: false },
        },
        legend: {
            show: true, position: 'bottom',
            fontSize: '0.72rem', fontFamily: 'inherit',
            labels: { colors: tc() },
            markers: { width: 8, height: 8, radius: 4 },
            itemMargin: { horizontal: 8 },
        },
        tooltip: sharedTooltip(),
        theme: { mode: mode() },
    });
    polarChart.render();

    /* ─── Re-render on theme toggle ─────────────────── */
    document.querySelectorAll('[data-theme]').forEach(function(el) {
        el.addEventListener('click', function() {
            setTimeout(function() {
                var m = mode();
                var axUpdate  = { labels: { style: { colors: tc() } } };
                var baseOpts  = { theme:{mode:m}, tooltip:{theme:m}, grid:{borderColor:gc()},
                                  xaxis: axUpdate, yaxis:[{labels:{style:{colors:tc()}}},{show:false},{labels:{style:{colors:tc()}}}] };
                areaChart.updateOptions(Object.assign({}, baseOpts, {
                    markers: { strokeColors: sc() },
                    yaxis: { labels: { style: { colors: tc() } } },
                }));
                mixedChart.updateOptions(baseOpts);
                distChart.updateOptions({ theme:{mode:m}, tooltip:{theme:m},
                    plotOptions:{pie:{donut:{labels:{total:{color:tc()}, value:{color:isDark()?'#e1def5':'#32304d'}, name:{color:tc()}}}}} });
                polarChart.updateOptions({ theme:{mode:m}, tooltip:{theme:m},
                    legend:{ labels:{colors:tc()} },
                    plotOptions:{polarArea:{rings:{strokeColor:gc()}, spokes:{connectorColors:gc()}}} });
            }, 50);
        });
    });

    /* ─── Animated counters ─────────────────────────── */
    document.querySelectorAll('[data-counter]').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        var start  = performance.now(), dur = 900;
        (function step(now) {
            var p = Math.min((now - start) / dur, 1);
            el.textContent = Math.round((1 - Math.pow(1-p, 3)) * target).toLocaleString();
            if (p < 1) requestAnimationFrame(step);
        })(start);
    });

    /* ─── Animated progress bars ────────────────────── */
    setTimeout(function() {
        document.querySelectorAll('.dsc__bar-fill').forEach(function(el) {
            el.style.width = (el.getAttribute('data-width') || 0) + '%';
        });
    }, 300);

})();
</script>
@endpush
