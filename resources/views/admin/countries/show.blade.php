@extends('admin.layouts.crud.show', ['model' => $country])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/countries.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-world" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.country_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($country->deleted_at)
            <a href="#" data-id="{{ $country->id }}"
               data-route="{{ route('admin.countries.restore', ['id' => $country->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.countries.edit', ['country' => $country]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $country->id }}"
               data-route="{{ route('admin.countries.destroy', ['country' => $country]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.countries.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-map"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.regions') }}</div>
                        <div class="admin-stat-card__value">{{ number_format($regions_count ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-buildings"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.cities') }}</div>
                        <div class="admin-stat-card__value">{{ number_format($cities_count ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $country->is_active ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $country->is_active ? 'ti-circle-check' : 'ti-circle-off' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $country->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--secondary">
                    <div class="admin-stat-card__icon"><i class="ti ti-hash"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.code') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $country->code }}">
                            {{ $country->code }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <img src="{{ $country->flag }}" alt="" class="admin-profile-card__avatar">
            </div>
            <div class="role-identity-card__name text-center mb-1">{{ $country->translate('ar')->name ?? '—' }}</div>
            <div class="text-muted small text-center mb-2">{{ __('admin/inputs.ar') }}</div>
            <div class="role-identity-card__name text-center mb-1">{{ $country->translate('en')->name ?? '—' }}</div>
            <div class="text-muted small text-center">{{ __('admin/inputs.en') }}</div>
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.country_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', [
                        'icon' => 'ti-hash',
                        'label' => __('admin/main.code'),
                        'value' => $country->code,
                    ])
                    @include('admin.admins.parts.detail-row', [
                        'icon' => 'ti-language',
                        'label' => __('admin/main.name') . ' (' . __('admin/inputs.ar') . ')',
                        'value' => $country->translate('ar')->name ?? '—',
                    ])
                    @include('admin.admins.parts.detail-row', [
                        'icon' => 'ti-language',
                        'label' => __('admin/main.name') . ' (' . __('admin/inputs.en') . ')',
                        'value' => $country->translate('en')->name ?? '—',
                    ])
                    @include('admin.admins.parts.detail-row', [
                        'icon' => 'ti-photo',
                        'label' => __('admin/main.flag'),
                        'value' => $country->flag,
                    ])
                    @include('admin.admins.parts.detail-row', [
                        'icon' => 'ti-calendar',
                        'label' => __('admin/main.created_at'),
                        'value' => $country->created_at?->format('Y-m-d H:i'),
                    ])
                    @include('admin.admins.parts.detail-row', [
                        'icon' => 'ti-calendar-event',
                        'label' => __('admin/main.updated_at'),
                        'value' => $country->updated_at?->format('Y-m-d H:i'),
                    ])
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="mb-0">
                    <i class="ti ti-map-pin me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.related_regions') }}
                </h6>
                <span class="badge bg-label-primary">{{ number_format($country_regions->count() ?? 0) }}</span>
            </div>
            <div class="admin-details-card__body p-0">
                @if (($country_regions ?? collect())->isEmpty())
                    <div class="text-muted text-center py-4 px-3">{{ __('admin/main.no_regions_for_country') }}</div>
                @else
                    <div class="table-responsive country-related-table">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('admin/main.id') }}</th>
                                    <th scope="col">{{ __('admin/main.name') }} ({{ __('admin/inputs.ar') }})</th>
                                    <th scope="col">{{ __('admin/main.name') }} ({{ __('admin/inputs.en') }})</th>
                                    <th scope="col" class="text-center">{{ __('admin/main.cities') }}</th>
                                    <th scope="col">{{ __('admin/main.is_active') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($country_regions as $region)
                                    <tr>
                                        <td class="text-nowrap text-muted small">{{ $region->id }}</td>
                                        <td>{{ $region->translate('ar')->name ?? '—' }}</td>
                                        <td>{{ $region->translate('en')->name ?? '—' }}</td>
                                        <td class="text-center">
                                            <span class="countries-count-badge countries-count-badge--sm">{{ number_format($region->cities_count) }}</span>
                                        </td>
                                        <td>
                                            @if ($region->is_active)
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
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="mb-0">
                    <i class="ti ti-building-community me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.related_cities') }}
                </h6>
                <span class="badge bg-label-success">{{ number_format($country_cities->count() ?? 0) }}</span>
            </div>
            <div class="admin-details-card__body p-0">
                @if (($country_cities ?? collect())->isEmpty())
                    <div class="text-muted text-center py-4 px-3">{{ __('admin/main.no_cities_for_country') }}</div>
                @else
                    <div class="table-responsive country-related-table">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('admin/main.id') }}</th>
                                    <th scope="col">{{ __('admin/main.name') }} ({{ __('admin/inputs.ar') }})</th>
                                    <th scope="col">{{ __('admin/main.name') }} ({{ __('admin/inputs.en') }})</th>
                                    <th scope="col">{{ __('admin/main.region') }}</th>
                                    <th scope="col">{{ __('admin/main.is_active') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($country_cities as $city)
                                    <tr>
                                        <td class="text-nowrap text-muted small">{{ $city->id }}</td>
                                        <td>{{ $city->translate('ar')->name ?? '—' }}</td>
                                        <td>{{ $city->translate('en')->name ?? '—' }}</td>
                                        <td class="text-truncate" style="max-width: 14rem;" title="{{ $city->region?->name }}">
                                            {{ $city->region?->name ?? '—' }}
                                        </td>
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
                @endif
            </div>
        </div>
    </div>
@endpush
