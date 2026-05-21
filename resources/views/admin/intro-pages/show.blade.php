@extends('admin.layouts.crud.show', ['model' => $intropage])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-info-circle" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.intro_page_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($intropage->deleted_at)
            <a href="#" data-id="{{ $intropage->id }}"
               data-route="{{ route('admin.intro-pages.restore', ['id' => $intropage->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.intro-pages.edit', ['intro_page' => $intropage]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $intropage->id }}"
               data-route="{{ route('admin.intro-pages.destroy', ['intro_page' => $intropage]) }}"
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
                <div class="admin-stat-card admin-stat-card--{{ $intropage->is_active ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $intropage->is_active ? 'ti-circle-check' : 'ti-circle-off' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $intropage->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-link"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.link') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $intropage->link }}">
                            {{ $intropage->link ? Str::limit($intropage->link, 30) : '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-article"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.title') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $intropage->title }}">
                            {{ $intropage->title ? Str::limit($intropage->title, 30) : '-' }}
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
                            {{ $intropage->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                @if ($intropage->image)
                    <img src="{{ $intropage->image }}" alt="{{ $intropage->title }}" class="admin-profile-card__avatar">
                @else
                    <i class="ti ti-info-circle" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
                @endif
            </div>
            <h5 class="admin-profile-card__name">{{ $intropage->title ?: '-' }}</h5>
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
                    @include('admin.admins.parts.file-detail-row', ['icon' => 'ti-photo', 'label' => __('admin/main.image'), 'value' => $intropage->image])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-link', 'label' => __('admin/main.link'), 'value' => $intropage->link])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.title'), 'value' => $intropage->title])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-align-left', 'label' => __('admin/main.description'), 'value' => $intropage->description])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $intropage->created_at?->format('Y-m-d H:i')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar-event', 'label' => __('admin/main.updated_at'), 'value' => $intropage->updated_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
