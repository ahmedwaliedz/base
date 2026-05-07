/**
 * Admin Home Dashboard
 * Reads server data from window.HOME_STATS injected by the Blade view.
 *  - charts use ApexCharts
 *  - counters / progress bars use IntersectionObserver
 *  - charts re-render on theme toggle
 *  - mixed activity chart has tabs (complaints | contacts | users)
 */
(function () {
    'use strict';

    var DATA = window.HOME_STATS;
    if (!DATA || typeof ApexCharts === 'undefined') return;

    /* ─── CSS-token color helpers ─────────────────── */
    var rootStyle = function () { return getComputedStyle(document.documentElement); };
    function tokenRgb(name, fallback) {
        var v = rootStyle().getPropertyValue(name).trim();
        return v ? 'rgb(' + v + ')' : fallback;
    }
    function token(name, fallback) {
        var v = rootStyle().getPropertyValue(name).trim();
        return v || fallback;
    }

    /* ─── Theme helpers ───────────────────────────── */
    function isDark() {
        var t = document.documentElement.getAttribute('data-theme') || '';
        if (t === 'dark') return true;
        if (t === 'light') return false;
        return window.matchMedia('(prefers-color-scheme:dark)').matches;
    }
    var tc   = function () { return isDark() ? 'rgba(225,222,245,0.55)' : 'rgba(50,48,77,0.55)'; };
    var gc   = function () { return isDark() ? 'rgba(255,255,255,0.05)' : 'rgba(50,48,77,0.06)'; };
    var sc   = function () { return isDark() ? '#2C3148' : '#fff'; };
    var mode = function () { return isDark() ? 'dark' : 'light'; };
    var strong = function () { return isDark() ? '#F7F8FF' : '#2F2B3D'; };

    var sharedAxis = function () {
        return {
            labels: { style: { colors: tc(), fontSize: '0.74rem', fontFamily: 'inherit' } },
            axisBorder: { show: false }, axisTicks: { show: false }
        };
    };
    var sharedGrid = function () {
        return { borderColor: gc(), strokeDashArray: 4, xaxis: { lines: { show: false } } };
    };
    var sharedTooltip = function () {
        return { theme: mode(), style: { fontSize: '0.78rem', fontFamily: 'inherit' } };
    };

    /* ─── Color palette (sourced from tokens.css) ───────── */
    var palette = {
        users:        tokenRgb('--color-brand-primary-rgb',    '#7367f0'),
        admins:       'rgb(' + token('--home-users-admin-rgb', '79,70,229') + ')',
        complaints:   'rgb(' + token('--home-complaint-rgb',   '255,106,0') + ')',
        contacts:     'rgb(' + token('--home-contact-rgb',     '30,136,229') + ')',
        categories:   'rgb(' + token('--home-category-rgb',    '232,97,216') + ')',
        faqs:         'rgb(' + token('--home-faq-rgb',         '32,201,151') + ')',
        posts:        'rgb(' + token('--home-post-rgb',        '168,85,247') + ')',
        sliders:      'rgb(' + token('--home-slider-rgb',      '236,72,153') + ')',
    };

    /* ─── 1. Multi-series Area: Users + Admins monthly ─── */
    var areaChart = new ApexCharts(document.querySelector('#chartMonthly'), {
        chart: {
            type: 'area', height: 240,
            toolbar: { show: false }, background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 800,
                          animateGradually: { enabled: true, delay: 120 } },
        },
        series: [
            { name: DATA.labels.users,  data: DATA.users_monthly_counts },
            { name: DATA.labels.admins, data: DATA.admins_monthly_counts },
        ],
        xaxis: Object.assign({ categories: DATA.monthly_labels }, sharedAxis()),
        yaxis: {
            labels: {
                style: { colors: tc(), fontSize: '0.72rem' },
                formatter: function (v) { return Math.round(v); }
            },
            min: 0
        },
        grid: sharedGrid(),
        stroke: { curve: 'smooth', width: [2.5, 2] },
        colors: [palette.users, palette.admins],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: [0.32, 0.22],
                opacityTo:   [0.01, 0.01],
                stops: [0, 90, 100]
            }
        },
        markers: { size: [4, 3], strokeColors: sc(), strokeWidth: 2, hover: { sizeOffset: 2 } },
        dataLabels: { enabled: false },
        tooltip: Object.assign({ shared: true, intersect: false }, sharedTooltip()),
        legend: { show: false },
        theme: { mode: mode() },
    });
    areaChart.render();

    /* ─── 2. Donut: Platform distribution ─── */
    var distChart = new ApexCharts(document.querySelector('#chartDist'), {
        chart: {
            type: 'donut', height: 240, background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 800 },
        },
        series: DATA.dist_series,
        labels: DATA.dist_labels,
        colors: [
            palette.users, palette.complaints, palette.contacts,
            palette.categories, palette.faqs,  palette.posts, palette.sliders
        ],
        stroke: { width: 0 },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: DATA.labels.total,
                            color: tc(), fontSize: '0.72rem',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                            }
                        },
                        value: {
                            fontSize: '1.4rem', fontWeight: 800,
                            color: strong(), offsetY: 4
                        },
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

    /* ─── 3. Activity chart with TABS ─── */
    var activityTabs = {
        complaints: {
            color: palette.complaints,
            data:  DATA.activity_complaints,
            label: DATA.labels.complaints,
        },
        contacts: {
            color: palette.contacts,
            data:  DATA.activity_contacts,
            label: DATA.labels.contacts,
        },
        users: {
            color: palette.users,
            data:  DATA.users_monthly_counts,
            label: DATA.labels.users,
        },
    };

    function activityOptions(key) {
        var t = activityTabs[key];
        return {
            chart: {
                type: 'bar', height: 240,
                toolbar: { show: false }, background: 'transparent',
                animations: { enabled: true, easing: 'easeinout', speed: 700 },
            },
            series: [{ name: t.label, data: t.data }],
            xaxis: Object.assign({ categories: DATA.activity_labels }, sharedAxis()),
            yaxis: {
                labels: {
                    style: { colors: tc(), fontSize: '0.72rem' },
                    formatter: function (v) { return Math.round(v); }
                },
                min: 0
            },
            grid: sharedGrid(),
            colors: [t.color],
            plotOptions: {
                bar: {
                    columnWidth: '42%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end',
                    distributed: false
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    gradientToColors: [t.color],
                    inverseColors: false,
                    opacityFrom: 0.95,
                    opacityTo: 0.55,
                    stops: [0, 100]
                }
            },
            stroke: { width: 0 },
            dataLabels: { enabled: false },
            legend: { show: false },
            tooltip: Object.assign({ shared: false, intersect: true }, sharedTooltip()),
            theme: { mode: mode() },
        };
    }

    var activityChart = new ApexCharts(
        document.querySelector('#chartActivity'),
        activityOptions('complaints')
    );
    activityChart.render();

    document.querySelectorAll('.dash-chart__tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-tab');
            if (!activityTabs[key]) return;
            document.querySelectorAll('.dash-chart__tab')
                .forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            var t = activityTabs[key];
            activityChart.updateOptions({
                series: [{ name: t.label, data: t.data }],
                colors: [t.color],
                fill: {
                    type: 'gradient',
                    gradient: { gradientToColors: [t.color], opacityFrom: 0.95, opacityTo: 0.55 }
                }
            });
        });
    });

    /* ─── 4. Polar Area: Active ratios only (consistent unit %) ─── */
    var polarChart = new ApexCharts(document.querySelector('#chartPolar'), {
        chart: {
            type: 'polarArea', height: 280, background: 'transparent',
            animations: { enabled: true, easing: 'easeinout', speed: 900 },
            toolbar: { show: false },
        },
        series: DATA.polar_series,
        labels: DATA.polar_labels,
        colors: [
            palette.users, palette.categories,
            palette.sliders, palette.complaints
        ],
        stroke: { width: 0 },
        fill: { opacity: 0.78 },
        yaxis: {
            show: true,
            max: 100,
            labels: { style: { colors: tc(), fontSize: '0.65rem' }, formatter: function (v) { return v + '%'; } }
        },
        plotOptions: {
            polarArea: {
                rings:  { strokeWidth: 1, strokeColor: gc() },
                spokes: { strokeWidth: 1, connectorColors: gc() }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (v) { return Math.round(v) + '%'; },
            style: { fontSize: '0.74rem', fontFamily: 'inherit', colors: ['#fff'] },
            dropShadow: { enabled: false },
        },
        legend: {
            show: true, position: 'bottom',
            fontSize: '0.74rem', fontFamily: 'inherit',
            labels: { colors: tc() },
            markers: { width: 8, height: 8, radius: 4 },
            itemMargin: { horizontal: 8 },
        },
        tooltip: Object.assign(sharedTooltip(), {
            y: { formatter: function (v) { return Math.round(v) + '%'; } }
        }),
        theme: { mode: mode() },
    });
    polarChart.render();

    /* ─── Re-render on theme toggle ─── */
    document.querySelectorAll('[data-theme]').forEach(function (el) {
        el.addEventListener('click', function () {
            setTimeout(function () {
                var m = mode();
                var ax = { labels: { style: { colors: tc() } } };
                var base = {
                    theme: { mode: m },
                    tooltip: { theme: m },
                    grid: { borderColor: gc() },
                    xaxis: ax,
                    yaxis: { labels: { style: { colors: tc() } } }
                };
                areaChart.updateOptions(Object.assign({}, base, {
                    markers: { strokeColors: sc() }
                }));
                activityChart.updateOptions(base);
                distChart.updateOptions({
                    theme: { mode: m },
                    tooltip: { theme: m },
                    plotOptions: { pie: { donut: { labels: {
                        total: { color: tc() },
                        value: { color: strong() },
                        name:  { color: tc() }
                    } } } }
                });
                polarChart.updateOptions({
                    theme: { mode: m },
                    tooltip: { theme: m },
                    legend: { labels: { colors: tc() } },
                    plotOptions: { polarArea: {
                        rings:  { strokeColor: gc() },
                        spokes: { connectorColors: gc() }
                    } }
                });
            }, 50);
        });
    });

    /* ─── Animated counters via IntersectionObserver ─── */
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function animateCounter(el) {
        var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        if (prefersReducedMotion) {
            el.textContent = target.toLocaleString();
            return;
        }
        var start = performance.now(), dur = 900;
        (function step(now) {
            var p = Math.min((now - start) / dur, 1);
            el.textContent = Math.round((1 - Math.pow(1 - p, 3)) * target).toLocaleString();
            if (p < 1) requestAnimationFrame(step);
        })(start);
    }

    function animateBar(el) {
        if (prefersReducedMotion) {
            el.style.width = (el.getAttribute('data-width') || 0) + '%';
            return;
        }
        requestAnimationFrame(function () {
            el.style.width = (el.getAttribute('data-width') || 0) + '%';
        });
    }

    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                var el = e.target;
                if (el.hasAttribute('data-counter')) animateCounter(el);
                if (el.classList.contains('dsc__bar-fill')) animateBar(el);
                io.unobserve(el);
            });
        }, { threshold: 0.2 });
        document.querySelectorAll('[data-counter], .dsc__bar-fill').forEach(function (el) {
            io.observe(el);
        });
    } else {
        document.querySelectorAll('[data-counter]').forEach(animateCounter);
        document.querySelectorAll('.dsc__bar-fill').forEach(animateBar);
    }
})();
