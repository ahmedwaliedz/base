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
    <div class="col-12 mb-4">
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
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-clock', 'label' => __('admin/main.status'), 'value' => __('admin/main.' . ($complaint->status?->value ?? 'pending'))])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-tag', 'label' => __('admin/main.type'), 'value' => __('admin/main.' . ($complaint->type?->value ?? 'complaint'))])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.subject'), 'value' => $complaint->subject])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-message', 'label' => __('admin/main.complaint'), 'value' => $complaint->complaint])
                    @include('admin.admins.parts.detail-row', ['icon' => $complaint->is_read ? 'ti-mail-opened' : 'ti-mail', 'label' => __('admin/main.read'), 'value' => $complaint->is_read ? __('admin/main.read') : __('admin/main.unread')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $complaint->created_at?->format('Y-m-d H:i')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar-event', 'label' => __('admin/main.updated_at'), 'value' => $complaint->updated_at?->format('Y-m-d H:i')])
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
                                <img src="{{ asset('storage/uploads/complaints/' . $image->file_name) }}" class="rounded-2 img-fluid" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpush
