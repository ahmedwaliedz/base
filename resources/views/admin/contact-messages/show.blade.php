@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.name') }}</label><p class="form-control-plaintext">{{ $contactMessage->name }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.email') }}</label><p class="form-control-plaintext">{{ $contactMessage->email }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.phone') }}</label><p class="form-control-plaintext">{{ $contactMessage->phone ?? '—' }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.read') }}</label><span class="badge bg-label-{{ $contactMessage->is_read ? 'success' : 'warning' }}">{{ $contactMessage->is_read ? __('admin/main.read') : __('admin/main.unread') }}</span></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.subject') }}</label><p class="form-control-plaintext">{{ $contactMessage->subject }}</p></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.message') }}</label><p class="form-control-plaintext">{{ $contactMessage->message }}</p></div>
    </div>
</div>
@endpush