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
        @if (method_exists($district, 'trashed') && $district->trashed())
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
    <div class="col-12 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.district_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-circle-check', 'label' => __('admin/main.status'), 'value' => $district->is_active ? __('admin/main.active') : __('admin/main.inactive')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-tag', 'label' => __('admin/main.name'), 'value' => $district->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-building', 'label' => __('admin/main.city'), 'value' => $district->city?->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-map-pin', 'label' => __('admin/main.region'), 'value' => $district->city?->region?->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $district->created_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
