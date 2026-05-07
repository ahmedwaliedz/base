@extends('admin.layouts.master')

@push('css')
    <style>
        /* ── Welcome Banner ─────────────────────────────────────────── */
        .admin-welcome-banner {
            background: linear-gradient(125deg, #1a1a2e 0%, #252542 38%, #16213e 72%, #1f2937 100%);
            color: #fff;
            padding: 1.5rem 1.75rem;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.4);
            overflow: hidden;
        }
        .admin-welcome-banner__title {
            font-size: clamp(1.25rem, 2.5vw, 1.625rem);
            font-weight: 700;
            margin-bottom: 0.35rem;
            line-height: 1.3;
        }
        .admin-welcome-banner__subtitle {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.9375rem;
            margin-bottom: 0;
            max-width: 28rem;
            line-height: 1.5;
        }
        .admin-welcome-banner__stat-label {
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.8125rem;
            margin-bottom: 0.25rem;
            line-height: 1.35;
            max-width: 11rem;
        }
        .admin-welcome-banner__stat-value {
            font-size: clamp(1.375rem, 3vw, 1.875rem);
            font-weight: 700;
            line-height: 1.2;
        }
        @keyframes admin-welcome-fade-up {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0);   }
        }
        .admin-welcome-banner__anim {
            opacity: 0;
            animation: admin-welcome-fade-up 0.65s ease-out forwards;
        }
        .admin-welcome-banner__anim--1 { animation-delay:   0ms; }
        .admin-welcome-banner__anim--2 { animation-delay:  80ms; }
        .admin-welcome-banner__anim--3 { animation-delay: 160ms; }
        @media (prefers-reduced-motion: reduce) {
            .admin-welcome-banner__anim {
                animation: none !important; opacity: 1 !important; transform: none !important;
            }
        }

        /* ── Glass Stat Cards ───────────────────────────────────────── */
        .dash-stat-card {
            position: relative;
            border-radius: 1rem;
            padding: 1.4rem 1.5rem;
            overflow: hidden;
            backdrop-filter: blur(16px) saturate(160%);
            -webkit-backdrop-filter: blur(16px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.10);
            background: rgba(255, 255, 255, 0.04);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18), inset 0 1px 0 rgba(255,255,255,0.07);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            cursor: default;
            animation: admin-welcome-fade-up 0.55s ease-out both;
        }
        .dash-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.28), inset 0 1px 0 rgba(255,255,255,0.10);
        }
        .dash-stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(var(--dsc-rgb), 0.14) 0%, transparent 60%);
            pointer-events: none;
        }
        .dash-stat-card--total   { --dsc-rgb: 115, 103, 240; animation-delay: 0ms;   }
        .dash-stat-card--active  { --dsc-rgb: 40,  199, 111; animation-delay: 70ms;  }
        .dash-stat-card--new     { --dsc-rgb: 0,   207, 232; animation-delay: 140ms; }
        .dash-stat-card--blocked { --dsc-rgb: 234,  84,  85; animation-delay: 210ms; }

        .dash-stat-card__icon {
            width: 46px; height: 46px;
            border-radius: 0.75rem;
            background: rgba(var(--dsc-rgb), 0.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
            color: rgb(var(--dsc-rgb));
            flex-shrink: 0;
            box-shadow: 0 0 0 1px rgba(var(--dsc-rgb), 0.25);
        }
        .dash-stat-card__label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            opacity: 0.55;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .dash-stat-card__value {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.15;
            color: rgb(var(--dsc-rgb));
            text-shadow: 0 0 24px rgba(var(--dsc-rgb), 0.35);
        }
        .dash-stat-card__sub {
            font-size: 0.78rem;
            opacity: 0.5;
            margin-top: 0.2rem;
        }
        /* shimmer stripe */
        .dash-stat-card::after {
            content: '';
            position: absolute;
            top: -40%; inset-inline-start: -60%;
            width: 40%; height: 180%;
            background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.07) 50%, transparent 60%);
            transform: skewX(-20deg);
            transition: inset-inline-start 0.5s ease;
        }
        .dash-stat-card:hover::after {
            inset-inline-start: 130%;
        }

        /* ── Chart Cards ────────────────────────────────────────────── */
        .dash-chart-card {
            border-radius: 1rem;
            backdrop-filter: blur(16px) saturate(160%);
            -webkit-backdrop-filter: blur(16px) saturate(160%);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
            padding: 1.4rem 1.5rem;
        }
        .dash-chart-card__title {
            font-size: 0.875rem;
            font-weight: 600;
            opacity: 0.85;
            margin-bottom: 0.25rem;
        }
        .dash-chart-card__sub {
            font-size: 0.75rem;
            opacity: 0.45;
            margin-bottom: 1rem;
        }

        /* light-mode tint */
        [data-theme="light"] .dash-stat-card,
        [data-theme="light"] .dash-chart-card {
            background: rgba(255, 255, 255, 0.65);
            border-color: rgba(115, 103, 240, 0.10);
            box-shadow: 0 4px 20px rgba(115, 103, 240, 0.08);
        }
        [data-theme="light"] .dash-stat-card:hover,
        [data-theme="light"] .dash-chart-card:hover {
            box-shadow: 0 10px 32px rgba(115, 103, 240, 0.16);
        }
    </style>
@endpush

@section('content')
    @php
        $admin = auth('admin')->user();
    @endphp

    {{-- Welcome Banner --}}
    <div class="admin-welcome-banner mb-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-4">
            <div class="admin-welcome-banner__intro admin-welcome-banner__anim admin-welcome-banner__anim--1">
                <h1 class="admin-welcome-banner__title mb-0">
                    {{ __('admin/main.home_welcome_back', ['name' => $admin->name]) }}
                </h1>
                <p class="admin-welcome-banner__subtitle mt-2 mb-0">
                    {{ __('admin/main.home_welcome_subtitle') }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-4 gap-md-5 align-items-start">
                <div class="admin-welcome-banner__anim admin-welcome-banner__anim--2">
                    <div class="admin-welcome-banner__stat-label">
                        {{ __('admin/main.home_stat_users_this_year') }}
                    </div>
                    <div class="admin-welcome-banner__stat-value">
                        {{ number_format($stats['users_this_year']) }}
                    </div>
                </div>
                <div class="admin-welcome-banner__anim admin-welcome-banner__anim--3">
                    <div class="admin-welcome-banner__stat-label">
                        {{ __('admin/main.home_stat_top_package_purchases') }}
                    </div>
                    <div class="admin-welcome-banner__stat-value">
                        {{ number_format($stats['top_package_purchases']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Glass Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="dash-stat-card dash-stat-card--total h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="dash-stat-card__icon">
                        <i class="ti ti-users"></i>
                    </div>
                </div>
                <div class="dash-stat-card__label">{{ __('admin/main.home_stat_total_users') }}</div>
                <div class="dash-stat-card__value" data-counter="{{ $stats['total_users'] }}">0</div>
                <div class="dash-stat-card__sub">{{ __('admin/main.home_stat_all_time') }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="dash-stat-card dash-stat-card--active h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="dash-stat-card__icon">
                        <i class="ti ti-user-check"></i>
                    </div>
                </div>
                <div class="dash-stat-card__label">{{ __('admin/main.home_stat_active_users') }}</div>
                <div class="dash-stat-card__value" data-counter="{{ $stats['active_users'] }}">0</div>
                <div class="dash-stat-card__sub">
                    @if($stats['total_users'] > 0)
                        {{ round(($stats['active_users'] / $stats['total_users']) * 100) }}% {{ __('admin/main.home_stat_of_total') }}
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="dash-stat-card dash-stat-card--new h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="dash-stat-card__icon">
                        <i class="ti ti-user-plus"></i>
                    </div>
                </div>
                <div class="dash-stat-card__label">{{ __('admin/main.home_stat_new_this_month') }}</div>
                <div class="dash-stat-card__value" data-counter="{{ $stats['new_this_month'] }}">0</div>
                <div class="dash-stat-card__sub">{{ now()->translatedFormat('F Y') }}</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="dash-stat-card dash-stat-card--blocked h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="dash-stat-card__icon">
                        <i class="ti ti-user-x"></i>
                    </div>
                </div>
                <div class="dash-stat-card__label">{{ __('admin/main.home_stat_blocked_users') }}</div>
                <div class="dash-stat-card__value" data-counter="{{ $stats['blocked_users'] }}">0</div>
                <div class="dash-stat-card__sub">{{ __('admin/main.home_stat_restricted') }}</div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">
        {{-- Area Chart: Monthly Registrations --}}
        <div class="col-12 col-xl-8">
            <div class="dash-chart-card h-100">
                <div class="dash-chart-card__title">{{ __('admin/main.home_chart_monthly_title') }}</div>
                <div class="dash-chart-card__sub">{{ __('admin/main.home_chart_monthly_sub') }}</div>
                <div id="chartMonthly"></div>
            </div>
        </div>
        {{-- Donut Chart: User Status --}}
        <div class="col-12 col-xl-4">
            <div class="dash-chart-card h-100">
                <div class="dash-chart-card__title">{{ __('admin/main.home_chart_status_title') }}</div>
                <div class="dash-chart-card__sub">{{ __('admin/main.home_chart_status_sub') }}</div>
                <div id="chartStatus"></div>
                <div class="d-flex justify-content-center gap-4 mt-3">
                    <div class="text-center">
                        <div style="width:10px;height:10px;border-radius:50%;background:#28c76f;display:inline-block;margin-inline-end:5px"></div>
                        <span style="font-size:0.78rem;opacity:0.65">{{ __('admin/main.home_stat_active_users') }}</span>
                    </div>
                    <div class="text-center">
                        <div style="width:10px;height:10px;border-radius:50%;background:#ea5455;display:inline-block;margin-inline-end:5px"></div>
                        <span style="font-size:0.78rem;opacity:0.65">{{ __('admin/main.home_stat_blocked_users') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.min.js"></script>
    <script>
    (function () {
        'use strict';

        /* ── Shared theme helpers ────────────────────────────────── */
        function isDark() {
            var theme = document.documentElement.getAttribute('data-theme') || 'theme-default';
            return theme === 'dark' || (theme !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        }
        var textColor  = function() { return isDark() ? 'rgba(225,222,245,0.55)' : 'rgba(50,48,77,0.55)'; };
        var gridColor  = function() { return isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(50,48,77,0.06)'; };
        var tooltipBg  = function() { return isDark() ? '#2f2b3d' : '#ffffff'; };

        /* ── Monthly Registrations — Area Chart ──────────────────── */
        var monthlyLabels = @json($stats['monthly_labels']);
        var monthlyCounts = @json($stats['monthly_counts']);

        var areaOptions = {
            chart: {
                type: 'area',
                height: 240,
                toolbar: { show: false },
                sparkline: { enabled: false },
                background: 'transparent',
                animations: { enabled: true, easing: 'easeinout', speed: 700 },
            },
            series: [{ name: '{{ __('admin/main.home_chart_monthly_series') }}', data: monthlyCounts }],
            xaxis: {
                categories: monthlyLabels,
                labels: { style: { colors: textColor(), fontSize: '0.76rem' } },
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: {
                labels: {
                    style: { colors: textColor(), fontSize: '0.76rem' },
                    formatter: function(v) { return Math.round(v); },
                },
                min: 0,
            },
            grid: {
                borderColor: gridColor(),
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
            },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#7367f0'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.38,
                    opacityTo: 0.02,
                    stops: [0, 95, 100],
                },
            },
            markers: { size: 4, colors: ['#7367f0'], strokeColors: '#fff', strokeWidth: 2 },
            dataLabels: { enabled: false },
            tooltip: {
                theme: isDark() ? 'dark' : 'light',
                style: { fontSize: '0.8rem' },
            },
            theme: { mode: isDark() ? 'dark' : 'light' },
        };

        var areaChart = new ApexCharts(document.querySelector('#chartMonthly'), areaOptions);
        areaChart.render();

        /* ── User Status — Donut Chart ───────────────────────────── */
        var activeCount  = {{ (int) $stats['active_users']  }};
        var blockedCount = {{ (int) $stats['blocked_users'] }};

        var donutOptions = {
            chart: {
                type: 'donut',
                height: 220,
                background: 'transparent',
                animations: { enabled: true, easing: 'easeinout', speed: 700 },
            },
            series: [activeCount, blockedCount],
            labels: ['{{ __('admin/main.home_stat_active_users') }}', '{{ __('admin/main.home_stat_blocked_users') }}'],
            colors: ['#28c76f', '#ea5455'],
            stroke: { width: 0 },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: '{{ __('admin/main.home_chart_total') }}',
                                color: textColor(),
                                fontSize: '0.78rem',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0);
                                },
                            },
                            value: { fontSize: '1.4rem', fontWeight: 700, color: isDark() ? '#e1def5' : '#32304d' },
                        },
                    },
                },
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            tooltip: {
                theme: isDark() ? 'dark' : 'light',
                style: { fontSize: '0.8rem' },
            },
            theme: { mode: isDark() ? 'dark' : 'light' },
        };

        var donutChart = new ApexCharts(document.querySelector('#chartStatus'), donutOptions);
        donutChart.render();

        /* ── Re-render on theme switch ───────────────────────────── */
        document.querySelectorAll('[data-theme]').forEach(function(el) {
            el.addEventListener('click', function() {
                setTimeout(function() {
                    var mode = isDark() ? 'dark' : 'light';
                    [areaChart, donutChart].forEach(function(c) {
                        c.updateOptions({
                            theme: { mode: mode },
                            tooltip: { theme: mode },
                            xaxis: { labels: { style: { colors: textColor() } } },
                            yaxis: { labels: { style: { colors: textColor() } } },
                            grid: { borderColor: gridColor() },
                        });
                    });
                    donutChart.updateOptions({
                        plotOptions: { pie: { donut: { labels: { total: { color: textColor() } } } } },
                    });
                }, 50);
            });
        });

        /* ── Animated counters ───────────────────────────────────── */
        function animateCounter(el) {
            var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
            var duration = 900;
            var start = performance.now();
            function step(now) {
                var progress = Math.min((now - start) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target).toLocaleString();
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }
        document.querySelectorAll('[data-counter]').forEach(animateCounter);
    })();
    </script>
@endpush
