@extends('admin.layouts.crud.show', ['model' => $faq])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-help-circle" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.faq_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($faq->deleted_at)
            <a href="#" data-id="{{ $faq->id }}"
               data-route="{{ route('admin.faqs.restore', ['id' => $faq->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.faqs.edit', ['faq' => $faq]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $faq->id }}"
               data-route="{{ route('admin.faqs.destroy', ['faq' => $faq]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $faq->is_active ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $faq->is_active ? 'ti-circle-check' : 'ti-circle-off' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $faq->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-question-mark"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.question') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $faq->question }}">
                            {{ Str::limit($faq->question, 40) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-message"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.answer') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ strip_tags($faq->answer) }}">
                            {{ Str::limit(strip_tags($faq->answer), 40) }}
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
                            {{ $faq->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <i class="ti ti-help-circle" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
            </div>
            <h5 class="admin-profile-card__name">{{ Str::limit($faq->question, 60) }}</h5>
            <div class="text-muted small text-center px-3">{{ __('admin/main.faq') }}</div>
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.faq_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-question-mark', 'label' => __('admin/main.question'), 'value' => $faq->question])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-message', 'label' => __('admin/main.answer'), 'value' => strip_tags($faq->answer)])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $faq->created_at?->format('Y-m-d H:i')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar-event', 'label' => __('admin/main.updated_at'), 'value' => $faq->updated_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
