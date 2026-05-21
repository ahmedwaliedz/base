@extends('admin.layouts.crud.show', ['model' => $complaint])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-alert-triangle" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.complaint_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($complaint->deleted_at)
            <a href="#" data-id="{{ $complaint->id }}"
               data-route="{{ route('admin.complaints.restore', ['id' => $complaint->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="#" data-id="{{ $complaint->id }}"
               data-route="{{ route('admin.complaints.destroy', ['complaint' => $complaint]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $complaint->type === 'suggestion' ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $complaint->type === 'suggestion' ? 'ti-bulb' : 'ti-alert-triangle' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.type') }}</div>
                        <div class="admin-stat-card__value">
                            <span class="badge bg-label-{{ $complaint->type === 'suggestion' ? 'info' : 'warning' }}">
                                {{ __('admin/main.' . $complaint->type) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $complaint->status === 'solved' ? 'success' : 'danger' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $complaint->status === 'solved' ? 'ti-circle-check' : 'ti-clock' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            <span class="badge bg-label-{{ $complaint->status === 'solved' ? 'success' : 'danger' }}">
                                {{ __('admin/main.' . $complaint->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-user"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.name') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $complaint->name }}">
                            {{ $complaint->name }}
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
                            {{ $complaint->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <i class="ti ti-alert-triangle" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
            </div>
            <h5 class="admin-profile-card__name">{{ $complaint->name }}</h5>
            <div class="admin-profile-card__email">{{ $complaint->email }}</div>
            @if ($complaint->phone)
                <div class="text-muted small">{{ $complaint->phone }}</div>
            @endif
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.complaint_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-user', 'label' => __('admin/main.name'), 'value' => $complaint->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-mail', 'label' => __('admin/main.email'), 'value' => $complaint->email])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-phone', 'label' => __('admin/main.phone'), 'value' => $complaint->phone])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-tag', 'label' => __('admin/main.type'), 'value' => __('admin/main.' . $complaint->type)])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.subject'), 'value' => $complaint->subject])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-message', 'label' => __('admin/main.complaint'), 'value' => $complaint->complaint])
                </div>
            </div>
        </div>
    </div>

    @if ($complaint->images->count() > 0)
        <div class="col-12 mb-4">
            <div class="admin-details-card">
                <div class="admin-details-card__head">
                    <h6 class="mb-0">
                        <i class="ti ti-photo me-1" style="color: var(--color-brand-primary);"></i>
                        {{ __('admin/main.images') }}
                    </h6>
                </div>
                <div class="admin-details-card__body">
                    <div class="row g-3">
                        @foreach ($complaint->images as $image)
                            <div class="col-6 col-md-3 col-lg-2">
                                <img src="{{ $image->image }}" class="rounded-2 img-fluid" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpush
