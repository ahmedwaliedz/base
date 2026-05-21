@extends('admin.layouts.crud.show', ['model' => $seo])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-seo" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.seo_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($seo->deleted_at)
            <a href="#" data-id="{{ $seo->id }}"
               data-route="{{ route('admin.seo.restore', ['id' => $seo->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.seo.edit', ['seo' => $seo]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $seo->id }}"
               data-route="{{ route('admin.seo.destroy', ['seo' => $seo]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.seo.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-link"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.type') }}</div>
                        <div class="admin-stat-card__value text-truncate">
                            <span class="badge bg-label-info">{{ class_basename($seo->seoable_type) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-article"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.meta_title') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $seo->meta_title }}">
                            {{ Str::limit($seo->meta_title, 40) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--info">
                    <div class="admin-stat-card__icon"><i class="ti ti-align-left"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.meta_description') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $seo->meta_description }}">
                            {{ Str::limit($seo->meta_description, 40) }}
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
                            {{ $seo->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                @if ($seo->image)
                    <img src="{{ $seo->image }}" alt="{{ $seo->meta_title }}" class="admin-profile-card__avatar">
                @else
                    <i class="ti ti-seo" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
                @endif
            </div>
            <h5 class="admin-profile-card__name">{{ Str::limit($seo->meta_title, 60) }}</h5>
            <div class="text-muted small">{{ class_basename($seo->seoable_type) }}</div>
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.seo_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.file-detail-row', ['icon' => 'ti-photo', 'label' => __('admin/main.image'), 'value' => $seo->image])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.meta_title'), 'value' => $seo->meta_title])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-align-left', 'label' => __('admin/main.meta_description'), 'value' => $seo->meta_description])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-tag', 'label' => __('admin/main.meta_keywords'), 'value' => $seo->meta_keywords])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-link', 'label' => __('admin/main.type'), 'value' => class_basename($seo->seoable_type) . ' #' . $seo->seoable_id])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $seo->created_at?->format('Y-m-d H:i')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar-event', 'label' => __('admin/main.updated_at'), 'value' => $seo->updated_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
