@props([
    'loaderCards' => 4,
])
<div id="usersStatsContainer">
    <div class="crud-stats mb-3">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div>
                <div class="crud-stats__title">{{ __('admin/main.statistics') }}</div>
                <div class="crud-stats__subtitle hide_on_load " id="usersStatsSubtitle">{{ __('admin/main.statistics_subtitle') }}</div>
                <div class="crud-stats__subtitle show_on_load">{{ __('admin/main.loading') }}</div>
            </div>
            <button class="btn crud-stats__toggle d-inline-flex align-items-center gap-2 hide_on_load" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#usersStatsCollapse"
                    aria-expanded="true"
                    aria-controls="usersStatsCollapse">
                <i class="ti ti-chart-line"></i>
                <span>{{ __('admin/main.statistics_toggle') }}</span>
                <i class="ti ti-chevron-down crud-stats__chev ms-1"></i>
            </button>
            <output class="spinner-border spinner-border-sm text-primary show_on_load" aria-label="Loading"></output>
        </div>

        <div id="usersStatsCollapse" class="collapse show">
            <div id="usersStatsError" class="show_on_error">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <div class="crud-stats__subtitle">{{ __('admin/main.error_loading_data') }}</div>
                    <button type="button" class="btn crud-stats__toggle d-inline-flex align-items-center gap-2 js-crud-stats-reload">
                        <i class="ti ti-refresh"></i>
                        <span>{{ __('admin/main.retry') }}</span>
                    </button>
                </div>
            </div>
            <div id="usersStatsLoader" class="show_on_load">
                <div class="row g-3">
                    @for ($i = 0; $i < $loaderCards; $i++)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="crud-stats__card d-flex align-items-center justify-content-between">
                                <div class="w-100">
                                    <div class="crud-stats__label mb-2 placeholder-glow"><span class="placeholder col-6"></span></div>
                                    <div class="crud-stats__value placeholder-glow"><span class="placeholder col-4"></span></div>
                                </div>
                                <span class="crud-stats__icon opacity-50">
                                    <output class="spinner-border spinner-border-sm text-primary show_on_load" aria-label="Loading"></output>
                                </span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div id="usersStatsContent" class="hide_on_load">
                <div class="row g-3">
                    {{ $slot }}
                </div>
            </div>
        </div>


    </div>
</div>
