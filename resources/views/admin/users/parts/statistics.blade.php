<div class="col-12 col-sm-6 col-lg-3">
    <div class="crud-stats__card crud-stats__card--total d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.total') }}</p>
            <p class="crud-stats__value">{{ $total ?? 0 }}</p>
        </div>
        <span class="crud-stats__icon"><i class="ti ti-user"></i></span>
    </div>
</div>
<div class="col-12 col-sm-6 col-lg-3">
    <div class="crud-stats__card crud-stats__card--active d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.active') }}</p>
            <p class="crud-stats__value">{{ $active ?? 0 }}</p>
        </div>
        <span class="crud-stats__icon" style="background: rgba(40, 199, 111, 0.14); color: #28c76f;">
            <i class="ti ti-circle-check"></i>
        </span>
    </div>
</div>
<div class="col-12 col-sm-6 col-lg-3">
    <div class="crud-stats__card crud-stats__card--inactive d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.inactive') }}</p>
            <p class="crud-stats__value">{{ $inactive ?? 0 }}</p>
        </div>
        <span class="crud-stats__icon" style="background: rgba(255, 159, 67, 0.14); color: #ff9f43;">
            <i class="ti ti-player-pause"></i>
        </span>
    </div>
</div>
<div class="col-12 col-sm-6 col-lg-3">
    <div class="crud-stats__card crud-stats__card--today d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.today') }}</p>
            <p class="crud-stats__value">{{ $today ?? 0 }}</p>
        </div>
        <span class="crud-stats__icon" style="background: rgba(0, 207, 232, 0.14); color: #00cfe8;">
            <i class="ti ti-calendar"></i>
        </span>
    </div>
</div>



