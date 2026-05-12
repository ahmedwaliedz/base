{{-- Charts row 2: activity tabs + polar --}}
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
