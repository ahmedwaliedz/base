@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.image') }}</label>@if($seo->image)<div class="avatar-wrapper"><img src="{{ $seo->image }}" class="rounded-2" alt=""></div>@endif</div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.meta_title') }}</label><p class="form-control-plaintext">{{ $seo->meta_title }}</p></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.meta_description') }}</label><p class="form-control-plaintext">{{ $seo->meta_description }}</p></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.meta_keywords') }}</label><p class="form-control-plaintext">{{ $seo->meta_keywords }}</p></div>
    </div>
</div>
@endpush