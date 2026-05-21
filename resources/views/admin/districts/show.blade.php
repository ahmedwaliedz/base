@extends('admin.layouts.crud.show', ['model' => $district])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-location" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.district_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($district->deleted_at)
            <a href="#" data-id="{{ $district->id }}"
               data-route="{{ route('admin.districts.restore', ['id' => $district->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.districts.edit', ['district' => $district]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $district->id }}"
               data-route="{{ route('admin.districts.destroy', ['district' => $district]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.districts.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $district->is_active ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $district->is_active ? 'ti-circle-check' : 'ti-circle-off' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $district->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-building"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.city') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $district->city?->name }}">
                            {{ $district->city?->name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-map-pin"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.region') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $district->city?->region?->name }}">
                            {{ $district->city?->region?->name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--secondary">
                    <div class="admin-stat-card__icon"><i class="ti ti-calendar"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.created_at') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $district->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <i class="ti ti-location" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
            </div>
            <h5 class="admin-profile-card__name">{{ $district->name }}</h5>
            <div class="text-muted small">{{ $district->city?->name ?? '-' }}</div>
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.district_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-tag', 'label' => __('admin/main.name'), 'value' => $district->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-building', 'label' => __('admin/main.city'), 'value' => $district->city?->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-map-pin', 'label' => __('admin/main.region'), 'value' => $district->city?->region?->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $district->created_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
