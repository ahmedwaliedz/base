<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--total d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.total_roles') }}</p>
            <p class="crud-stats__value">{{ number_format($totalRoles ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true">
            <i class="ti ti-shield-check"></i>
        </span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--active d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.assigned_admins') }}</p>
            <p class="crud-stats__value">{{ number_format($assignedAdmins ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true">
            <i class="ti ti-users"></i>
        </span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--blocked d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.unassigned_roles') }}</p>
            <p class="crud-stats__value">{{ number_format($unassignedRoles ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true">
            <i class="ti ti-user-off"></i>
        </span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--today d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.avg_permissions') }}</p>
            <p class="crud-stats__value">{{ number_format($avgPermissions ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true">
            <i class="ti ti-lock"></i>
        </span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--week d-flex align-items-center justify-content-between">
        <div class="min-w-0">
            <p class="crud-stats__label mb-1">{{ __('admin/main.most_populated') }}</p>
            <p class="crud-stats__value text-truncate"
               title="{{ $mostPopulated?->name ?? '' }}"
               style="font-size: 1rem; max-width: 140px;">
                {{ $mostPopulated?->name ?? '—' }}
            </p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true">
            <i class="ti ti-crown"></i>
        </span>
    </div>
</div>

<div class="col-6 col-md-4 col-xl-2">
    <div class="crud-stats__card crud-stats__card--month d-flex align-items-center justify-content-between">
        <div>
            <p class="crud-stats__label mb-1">{{ __('admin/main.created_this_month') }}</p>
            <p class="crud-stats__value">{{ number_format($createdThisMonth ?? 0) }}</p>
        </div>
        <span class="crud-stats__icon" aria-hidden="true">
            <i class="ti ti-calendar-month"></i>
        </span>
    </div>
</div>
