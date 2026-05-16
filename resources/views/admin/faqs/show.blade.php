@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.status') }}</label><span class="badge bg-label-{{ $faq->is_active ? 'success' : 'danger' }}">{{ $faq->is_active ? __('admin/main.active') : __('admin/main.inactive') }}</span></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.question') }}</label><p class="form-control-plaintext">{{ $faq->question }}</p></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.answer') }}</label><p class="form-control-plaintext">{{ $faq->answer }}</p></div>
    </div>
</div>
@endpush