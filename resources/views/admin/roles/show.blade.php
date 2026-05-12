@extends('admin.layouts.crud.show', ['model' => $role])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/roles.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2">
        <i class="ti ti-shield-check" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.role_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-success">
            <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
        </a>
        <a href="javascript:void(0);"
           data-id="{{ $role->id }}"
           data-route="{{ route('admin.roles.destroy', $role->id) }}"
           class="btn btn-sm btn-danger delete-row">
            <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
        </a>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')

    {{-- Mini stats row --}}
    <div class="col-12 mb-4">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="role-stat-card role-stat-card--primary">
                    <div class="role-stat-card__icon"><i class="ti ti-users"></i></div>
                    <div class="min-w-0">
                        <div class="role-stat-card__label">{{ __('admin/main.assigned_admins') }}</div>
                        <div class="role-stat-card__value">{{ $role->admins->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="role-stat-card role-stat-card--success">
                    <div class="role-stat-card__icon"><i class="ti ti-lock-check"></i></div>
                    <div class="min-w-0">
                        <div class="role-stat-card__label">{{ __('admin/main.granted_permissions') }}</div>
                        <div class="role-stat-card__value">{{ count($permissions ?? []) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="role-stat-card role-stat-card--info">
                    <div class="role-stat-card__icon"><i class="ti ti-chart-pie"></i></div>
                    <div class="min-w-0">
                        <div class="role-stat-card__label">{{ __('admin/main.coverage') }}</div>
                        <div class="role-stat-card__value">{{ $coverage ?? 0 }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="role-stat-card role-stat-card--warning">
                    <div class="role-stat-card__icon"><i class="ti ti-calendar-plus"></i></div>
                    <div class="min-w-0">
                        <div class="role-stat-card__label">{{ __('admin/main.created_at') }}</div>
                        <div class="role-stat-card__value" style="font-size: 0.95rem;">
                            {{ $role->created_at?->format('Y-m-d') ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Identity (left) + permissions (right) --}}
    <div class="col-xl-4 col-md-5 mb-4">
        <div class="role-identity-card">
            <div class="role-identity-card__avatar">
                <i class="ti ti-shield-check"></i>
            </div>

            <div class="role-identity-card__names">
                <div>
                    <div class="role-identity-card__lang">AR</div>
                    <div class="role-identity-card__name">{{ $role->translate('ar')->name ?? '—' }}</div>
                </div>
                <div class="role-identity-card__sep"></div>
                <div>
                    <div class="role-identity-card__lang">EN</div>
                    <div class="role-identity-card__name">{{ $role->translate('en')->name ?? '—' }}</div>
                </div>
            </div>

            @if($role->admins->isNotEmpty())
                <div class="role-identity-card__admins">
                    <div class="role-identity-card__admins-label">{{ __('admin/main.assigned_admins') }}</div>
                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 mt-2">
                        @foreach($role->admins->take(6) as $admin)
                            <li class="avatar avatar-sm pull-up"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $admin->name }}">
                                <img class="rounded-circle" src="{{ $admin->image_url }}" alt="{{ $admin->name }}">
                            </li>
                        @endforeach
                        @if($role->admins->count() > 6)
                            <li class="role-identity-card__more">+{{ $role->admins->count() - 6 }}</li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="perm-legend">
            <span class="perm-legend__item">
                <span class="perm-badge perm-badge--on"><i class="ti ti-check"></i></span>
                {{ __('admin/main.assigned_permissions') }}
            </span>
            <span class="perm-legend__item">
                <span class="perm-badge perm-badge--off"><i class="ti ti-x"></i></span>
                {{ __('admin/main.unassigned_permissions') }}
            </span>
        </div>

        <div class="row g-3">
            @foreach($permissionsByGroup as $groupKey => $routes)
                <div class="col-md-6">
                    <div class="perm-group h-100">
                        <div class="perm-group__header">
                            <i class="ti ti-lock-square"></i>
                            {{ \App\Traits\Role\RoleTrait::translateRouteName('admin.' . $groupKey) }}
                        </div>
                        <div class="perm-group__body">
                            @foreach($routes as $route)
                                @php $on = isset($permissions) && in_array($route['name'], $permissions); @endphp
                                <span class="perm-badge {{ $on ? 'perm-badge--on' : 'perm-badge--off' }}">
                                    @if($on)<i class="ti ti-check"></i>@endif
                                    {{ $route['label'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endpush
