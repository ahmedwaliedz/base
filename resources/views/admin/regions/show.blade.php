@extends('admin.layouts.crud.show', ['model' => $region])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-map-pin" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.region_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($region->deleted_at)
            <a href="#" data-id="{{ $region->id }}"
               data-route="{{ route('admin.regions.restore', ['id' => $region->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.regions.edit', ['region' => $region]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $region->id }}"
               data-route="{{ route('admin.regions.destroy', ['region' => $region]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.regions.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $region->is_active ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $region->is_active ? 'ti-circle-check' : 'ti-circle-off' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $region->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-building"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.cities') }}</div>
                        <div class="admin-stat-card__value">{{ number_format($region->cities->count()) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-flag"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.country') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $region->country?->name }}">
                            {{ $region->country?->name ?? '-' }}
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
                            {{ $region->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <i class="ti ti-map-pin" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
            </div>
            <h5 class="admin-profile-card__name">{{ $region->name }}</h5>
            <div class="text-muted small">{{ $region->country?->name ?? '-' }}</div>
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.region_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-tag', 'label' => __('admin/main.name'), 'value' => $region->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-flag', 'label' => __('admin/main.country'), 'value' => $region->country?->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-building', 'label' => __('admin/main.cities'), 'value' => number_format($region->cities->count())])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $region->created_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>

    @if (($region->cities ?? collect())->isNotEmpty())
        <div class="col-12 mb-4">
            <div class="admin-details-card">
                <div class="admin-details-card__head d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="mb-0">
                        <i class="ti ti-building me-1" style="color: var(--color-brand-primary);"></i>
                        {{ __('admin/main.cities') }}
                    </h6>
                    <span class="badge bg-label-primary">{{ number_format($region->cities->count()) }}</span>
                </div>
                <div class="admin-details-card__body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('admin/main.id') }}</th>
                                    <th scope="col">{{ __('admin/main.name') }}</th>
                                    <th scope="col" class="text-center">{{ __('admin/main.related_districts') }}</th>
                                    <th scope="col">{{ __('admin/main.is_active') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($region->cities as $city)
                                    <tr>
                                        <td class="text-nowrap text-muted small">{{ $city->id }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td class="text-center">{{ number_format($city->districts_count ?? 0) }}</td>
                                        <td>
                                            @if ($city->is_active)
                                                <span class="badge bg-label-success">{{ __('admin/main.active') }}</span>
                                            @else
                                                <span class="badge bg-label-secondary">{{ __('admin/main.inactive') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpush
