@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.name') }}</label><p class="form-control-plaintext">{{ $city->name }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.region') }}</label><p class="form-control-plaintext">{{ $city->region?->name }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.status') }}</label><span class="badge bg-label-{{ $city->is_active ? 'success' : 'danger' }}">{{ $city->is_active ? __('admin/main.active') : __('admin/main.inactive') }}</span></div>
    </div>
</div>
@endpush