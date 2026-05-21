@extends('admin.layouts.crud.show', ['model' => $introPage])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-info-circle" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.intro_page_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($introPage->deleted_at)
            <a href="#" data-id="{{ $introPage->id }}"
               data-route="{{ route('admin.intro-pages.restore', ['id' => $introPage->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.intro-pages.edit', ['intro_page' => $introPage]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $introPage->id }}"
               data-route="{{ route('admin.intro-pages.destroy', ['intro_page' => $introPage]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.intro-pages.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $introPage->is_active ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $introPage->is_active ? 'ti-circle-check' : 'ti-circle-off' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $introPage->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-link"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.link') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $introPage->link }}">
                            {{ $introPage->link ? Str::limit($introPage->link, 30) : '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-article"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.title') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $introPage->title }}">
                            {{ $introPage->title ? Str::limit($introPage->title, 30) : '-' }}
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
                            {{ $introPage->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                @if ($introPage->image)
                    <img src="{{ $introPage->image }}" alt="{{ $introPage->title }}" class="admin-profile-card__avatar">
                @else
                    <i class="ti ti-info-circle" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
                @endif
            </div>
            <h5 class="admin-profile-card__name">{{ $introPage->title ?: '-' }}</h5>
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.intro_page_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.file-detail-row', ['icon' => 'ti-photo', 'label' => __('admin/main.image'), 'value' => $introPage->image])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-link', 'label' => __('admin/main.link'), 'value' => $introPage->link])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.title'), 'value' => $introPage->title])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-align-left', 'label' => __('admin/main.description'), 'value' => $introPage->description])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $introPage->created_at?->format('Y-m-d H:i')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar-event', 'label' => __('admin/main.updated_at'), 'value' => $introPage->updated_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
