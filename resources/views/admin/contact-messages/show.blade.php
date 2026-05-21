@extends('admin.layouts.crud.show', ['model' => $contactmessage])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-mail" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.message_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($contactmessage->deleted_at)
            <a href="#" data-id="{{ $contactmessage->id }}"
               data-route="{{ route('admin.contact-messages.restore', ['id' => $contactmessage->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="#" data-id="{{ $contactmessage->id }}"
               data-route="{{ route('admin.contact-messages.destroy', ['contact_message' => $contactmessage]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $contactmessage->is_read ? 'success' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $contactmessage->is_read ? 'ti-mail-opened' : 'ti-mail' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.read') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $contactmessage->is_read ? __('admin/main.read') : __('admin/main.unread') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-user"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.name') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $contactmessage->name }}">
                            {{ $contactmessage->name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--info">
                    <div class="admin-stat-card__icon"><i class="ti ti-mail-forward"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.email') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $contactmessage->email }}">
                            {{ $contactmessage->email }}
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
                            {{ $contactmessage->created_at?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                <i class="ti ti-mail" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
            </div>
            <h5 class="admin-profile-card__name">{{ $contactmessage->name }}</h5>
            <div class="admin-profile-card__email">{{ $contactmessage->email }}</div>
            @if ($contactmessage->phone)
                <div class="text-muted small">{{ $contactmessage->phone }}</div>
            @endif
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.message_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-user', 'label' => __('admin/main.name'), 'value' => $contactmessage->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-mail', 'label' => __('admin/main.email'), 'value' => $contactmessage->email])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-phone', 'label' => __('admin/main.phone'), 'value' => $contactmessage->phone])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.subject'), 'value' => $contactmessage->subject])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-message', 'label' => __('admin/main.message'), 'value' => $contactmessage->message])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $contactmessage->created_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
