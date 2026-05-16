@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.image') }}</label><div class="avatar-wrapper"><img src="{{ $slider->image }}" class="rounded-2" alt=""></div></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.link') }}</label><p class="form-control-plaintext">{{ $slider->link }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.status') }}</label><span class="badge bg-label-{{ $slider->is_active ? 'success' : 'danger' }}">{{ $slider->is_active ? __('admin/main.active') : __('admin/main.inactive') }}</span></div>
    </div>
</div>
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.title') }}</label><p class="form-control-plaintext">{{ $slider->title }}</p></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.description') }}</label><p class="form-control-plaintext">{{ $slider->description }}</p></div>
    </div>
</div>
@endpush