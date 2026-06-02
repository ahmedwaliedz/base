@php $growthUp = ($growth ?? 0) >= 0; @endphp

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--total d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.total') }}</p>
            <p class="crud-stats__value">{{ number_format($total ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-shield"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--active d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.active') }}</p>
            <p class="crud-stats__value">{{ number_format($active ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-circle-check"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--blocked d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.blocked') }}</p>
            <p class="crud-stats__value">{{ number_format($blocked ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-lock"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--today d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.super_admins') }}</p>
            <p class="crud-stats__value">{{ number_format($superAdmins ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-crown"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--week d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.roles_in_use') }}</p>
            <p class="crud-stats__value">{{ number_format($rolesInUse ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-shield-check"></i></span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--month d-flex align-items-center justify-content-between">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <p class="crud-stats__label mb-0">{{ __('admin/main.this_month') }}</p>
                @if (($growth ?? 0) !== 0)
                    <span class="crud-stats__delta {{ $growthUp ? 'crud-stats__delta--up' : 'crud-stats__delta--down' }}">
                        <i class="ti {{ $growthUp ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                        {{ abs($growth) }}%
                    </span>
                @endif
            </div>
            <p class="crud-stats__value">{{ number_format($thisMonth ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true"><i class="ti ti-calendar-stats"></i></span>
    </div>
</div>
