@extends('admin.layouts.crud.show')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/pages.css') }}">
@endpush

@push('content')
    <div class="admins-form-section">
        <div class="admins-form-section__head">
            <i class="ti ti-file-text"></i>
            <span>{{ __('admin/main.page_details') }}</span>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">{{ __('admin/main.slug') }}</label>
                <p class="form-control-plaintext">{{ $page->slug }}</p>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('admin/main.type') }}</label>
                <span class="badge bg-label-{{ $page->type === 'public' ? 'success' : ($page->type === 'user' ? 'primary' : 'warning') }}">
                    {{ __('admin/main.' . $page->type) }}
                </span>
            </div>
            @if ($page->icon)
            <div class="col-md-6">
                <label class="form-label">{{ __('admin/main.icon') }}</label>
                <p class="form-control-plaintext"><i class="{{ $page->icon }}"></i> {{ $page->icon }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="admins-form-section">
        <div class="admins-form-section__head">
            <i class="ti ti-language"></i>
            <span>{{ __('admin/main.translations') }}</span>
        </div>
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">{{ __('admin/main.title') }}</label>
                <p class="form-control-plaintext">{{ $page->title }}</p>
            </div>
            <div class="col-md-12">
                <label class="form-label">{{ __('admin/main.content') }}</label>
                <p class="form-control-plaintext">{{ $page->content }}</p>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.pages.statistics') }}";
    </script>
@endpush