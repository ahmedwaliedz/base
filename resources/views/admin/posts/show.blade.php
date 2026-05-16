@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.image') }}</label><div class="avatar-wrapper"><img src="{{ $post->image }}" class="rounded-2" alt=""></div></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.status') }}</label><span class="badge bg-label-{{ $post->is_active ? 'success' : 'danger' }}">{{ $post->is_active ? __('admin/main.active') : __('admin/main.inactive') }}</span></div>
    </div>
</div>
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.title') }}</label><p class="form-control-plaintext">{{ $post->title }}</p></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.content') }}</label><p class="form-control-plaintext">{{ $post->content }}</p></div>
    </div>
</div>
@endpush