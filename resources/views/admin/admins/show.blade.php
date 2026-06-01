@extends('admin.layouts.crud.show', ['model' => $admin])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/roles.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-shield" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.admin_details') }}
        @if ($admin->id === 1)
            <span class="admin-type-badge admin-type-badge--super ms-md-2">
                <i class="ti ti-crown"></i>{{ __('admin/main.super_admin') }}
            </span>
        @endif
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($admin->deleted_at)
            <a href="#" data-id="{{ $admin->id }}"
               data-route="{{ route('admin.admins.restore', ['id' => $admin->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.admins.edit', $id) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            @if ($admin->id !== 1)
                <a href="#" data-id="{{ $admin->id }}"
                   data-route="{{ route('admin.admins.destroy', ['admin' => $admin]) }}"
                   class="btn btn-sm btn-danger delete-record">
                    <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
                </a>
            @endif
        @endif
        <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')

    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-shield-check"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.role') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $admin->role_name }}">
                            {{ $admin->role_name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-lock-check"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.granted_permissions') }}</div>
                        <div class="admin-stat-card__value">{{ $permissionsCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $admin->is_blocked ? 'danger' : 'info' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $admin->is_blocked ? 'ti-lock' : 'ti-circle-check' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $admin->is_blocked ? __('admin/main.blocked') : __('admin/main.active') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--warning">
                    <div class="admin-stat-card__icon"><i class="ti ti-calendar-plus"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.account_age') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $admin->created_at ? intdiv(now()->timestamp - $admin->created_at->timestamp, 86400) . ' ' . __('admin/main.days') : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="admin-profile-card__avatar">
                @if ($admin->type?->value === \App\Enums\AdminType::SUPER_ADMIN->value)
                    <span class="admin-profile-card__crown" title="{{ __('admin/main.super_admin') }}">
                        <i class="ti ti-crown"></i>
                    </span>
                @endif
            </div>
            <h5 class="admin-profile-card__name">{{ $admin->name }}</h5>
            <div class="admin-profile-card__email">{{ $admin->email }}</div>

            <div class="admin-profile-card__chips">
                <span class="admin-status-pill {{ $admin->is_blocked ? 'is-blocked' : 'is-active' }}">
                    <span class="admin-status-pill__dot"></span>
                    {{ $admin->statusData()['label'] }}
                </span>
                @if ($admin->type)
                    <span class="admin-type-badge admin-type-badge--{{ $admin->type->value === \App\Enums\AdminType::SUPER_ADMIN->value ? 'super' : 'regular' }}">
                        @if ($admin->type->value === \App\Enums\AdminType::SUPER_ADMIN->value)<i class="ti ti-crown"></i>@endif
                        {{ $admin->type->label() }}
                    </span>
                @endif
                @if (! empty($admin->role_name))
                    <span class="admin-role-badge"><i class="ti ti-shield-check"></i>{{ $admin->role_name }}</span>
                @endif
            </div>

            @if ($admin->id !== 1)
                <div class="admin-profile-card__toggle">
                    <label class="d-flex align-items-center justify-content-between gap-2 m-0 w-100">
                        <span class="text-muted small">
                            <i class="ti ti-power"></i> {{ __('admin/main.account_state') }}
                        </span>
                        <input class="form-check-input switch-block m-0" type="checkbox" role="switch"
                               data-id="{{ $admin->id }}"
                               data-route="{{ route('admin.admins.switchBlock', ['id' => $admin->id]) }}"
                               {{ !$admin->is_blocked ? 'checked' : '' }}>
                    </label>
                </div>
            @endif
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.admin_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-user', 'label' => __('admin/main.name'), 'value' => $admin->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-mail', 'label' => __('admin/main.email'), 'value' => $admin->email])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-phone', 'label' => __('admin/inputs.phone'), 'value' => $admin->full_phone ?? ('+' . $admin->country_code . ' ' . $admin->phone)])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-flag', 'label' => __('admin/inputs.country_code'), 'value' => $admin->country_code])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-crown', 'label' => __('admin/main.type'), 'value' => $admin->type?->label()])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-shield', 'label' => __('admin/main.role'), 'value' => $admin->role_name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-bell', 'label' => __('admin/inputs.is_notify'), 'value' => $admin->is_notify ? __('admin/main.yes') : __('admin/main.no')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $admin->created_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>

    @if (! empty($permissionsByGroup ?? []))
        <div class="col-12 mb-4">
            <div class="admin-permissions-panel">
                <div class="admin-permissions-panel__head">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="ti ti-lock-check" style="color: var(--color-brand-primary);"></i>
                        {{ __('admin/main.permissions') }}
                    </h6>
                    <span class="admin-permissions-panel__count">
                        {{ $permissionsCount ?? 0 }} {{ __('admin/main.permissions') }}
                    </span>
                </div>
                <div class="admin-permissions-panel__body">
                    <div class="row g-3">
                        @foreach ($permissionsByGroup as $groupKey => $routes)
                            <div class="col-md-6 col-xl-4">
                                <div class="perm-group h-100">
                                    <div class="perm-group__header">
                                        <i class="ti ti-lock-square"></i>
                                        {{ $permissionGroupLabels['admin.' . $groupKey] ?? $groupKey }}
                                    </div>
                                    <div class="perm-group__body">
                                        @foreach ($routes as $route)
                                            @php $on = in_array($route['name'], $permissions ?? [], true); @endphp
                                            <span class="perm-badge {{ $on ? 'perm-badge--on' : 'perm-badge--off' }}">
                                                @if ($on)<i class="ti ti-check"></i>@endif
                                                {{ $route['label'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

@endpush

@push('js')
    <script src="{{ asset('style/admin/custom-js/admin-table.js') }}"></script>
@endpush
