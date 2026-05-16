@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.name') }}</label><p class="form-control-plaintext">{{ $district->name }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.city') }}</label><p class="form-control-plaintext">{{ $district->city?->name }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.status') }}</label><span class="badge bg-label-{{ $district->is_active ? 'success' : 'danger' }}">{{ $district->is_active ? __('admin/main.active') : __('admin/main.inactive') }}</span></div>
    </div>
</div>
@endpush