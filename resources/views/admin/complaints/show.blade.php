@extends('admin.layouts.crud.show')

@push('content')
<div class="admins-form-section">
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.name') }}</label><p class="form-control-plaintext">{{ $complaint->name }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.email') }}</label><p class="form-control-plaintext">{{ $complaint->email }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.phone') }}</label><p class="form-control-plaintext">{{ $complaint->phone ?? '—' }}</p></div>
        <div class="col-md-6"><label class="form-label">{{ __('admin/main.type') }}</label><span class="badge bg-label-info">{{ __('admin/main.' . $complaint->type) }}</span></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.subject') }}</label><p class="form-control-plaintext">{{ $complaint->subject }}</p></div>
        <div class="col-md-12"><label class="form-label">{{ __('admin/main.complaint') }}</label><p class="form-control-plaintext">{{ $complaint->complaint }}</p></div>
    </div>
</div>
@if($complaint->images->count() > 0)
<div class="admins-form-section">
    <div class="admins-form-section__head"><span>{{ __('admin/main.images') }}</span></div>
    <div class="row g-3">
        @foreach($complaint->images as $image)
        <div class="col-md-2"><img src="{{ $image->image }}" class="rounded-2" alt=""></div>
        @endforeach
    </div>
</div>
@endif
@endpush