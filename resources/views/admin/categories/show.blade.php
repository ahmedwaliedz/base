@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.name') }}</label><p class="form-control-plaintext">{{ $category->name }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.parent') }}</label><p class="form-control-plaintext">{{ $category->parent?->name ?? '—' }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.slug') }}</label><p class="form-control-plaintext">{{ $category->slug }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.status') }}</label><span class="badge bg-label-{{ $category->is_active ? 'success' : 'danger' }}">{{ $category->is_active ? __('admin/main.active') : __('admin/main.inactive') }}</span></div>
    </div>
</div>
@endpush