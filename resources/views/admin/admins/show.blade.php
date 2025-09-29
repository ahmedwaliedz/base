@extends('admin.layouts.crud.show')

@push('header')
    <h5 class="mb-0">{{ __('admin/main.admin_details') }}</h5>
    <div>
        <a href="{{ route('admin.admins.edit', $id) }}" class="btn btn-success me-2">{{ __('admin/main.edit') }}</a>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">{{ __('admin/main.back') }}</a>
    </div>
@endpush

@push('content')
    <div class="row g-4 justify-content-center">
        <div class="col-md-12 mb-3">
            <div class="border rounded p-4 text-center shadow-sm h-100">
                <img src="{{ $admin->image }}" class="rounded-circle border mb-3" style="width: 140px; height: 140px; object-fit: cover;" alt="{{ $admin->name }}" />
                <h5 class="mb-1">{{ $admin->name }}</h5>
                <div class="text-muted mb-3">{{ $admin->email }}</div>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge {{ $admin->statusData()['class'] }}">{{ $admin->statusData()['label'] }}</span>
                    @if($admin->type)
                        <span class="badge bg-label-primary">{{ $admin->type->label() }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">{{ __('admin/main.name') }}</small>
                    <div class="fw-semibold">{{ $admin->name }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">{{ __('admin/main.email') }}</small>
                    <div class="fw-semibold">{{ $admin->email }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">{{ __('admin/inputs.phone') }}</small>
                    <div class="fw-semibold">{{ $admin->full_phone ?? ('+' . $admin->country_code . ' ' . $admin->phone) }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">{{ __('admin/inputs.country_code') }}</small>
                    <div class="fw-semibold">{{ $admin->country_code }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">{{ __('admin/main.type') }}</small>
                    <div class="fw-semibold">{{ $admin->type?->label() }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">{{ __('admin/main.role') }}</small>
                    <div class="fw-semibold">{{ $admin->role_name }}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">{{ __('admin/inputs.is_notify') }}</small>
                    <div class="fw-semibold">{{ $admin->is_notify ? __('admin/main.yes') : __('admin/main.no') }}</div>
                </div>
            </div>
        </div>
    </div>
@endpush