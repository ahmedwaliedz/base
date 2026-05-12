{{-- Charts row 1: monthly + distribution --}}
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
