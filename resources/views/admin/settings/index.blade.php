@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/single-upload.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/multi-upload.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/settings.css') }}" />
@endpush

@section('content')

{{-- ═══════════════════ PAGE HEADER ═══════════════════ --}}
<div class="settings-page-head">
    <span class="settings-page-head__icon" aria-hidden="true">
        <i class="ti ti-settings"></i>
    </span>
    <div>
        <h1 class="settings-page-head__title">{{ __('admin/main.settings') }}</h1>
        <p class="settings-page-head__desc">{{ __('admin/main.settings_page_desc') }}</p>
    </div>
</div>

{{-- ═══════════════════ SHELL ═══════════════════ --}}
<div class="settings-shell">
    {{-- Sidebar nav (vertical desktop / horizontal mobile) --}}
    <aside class="settings-nav" aria-label="{{ __('admin/main.settings') }}">
        <ul class="settings-nav__list" role="tablist">
            @include('admin.settings.parts.tab-buttons.main')
            @include('admin.settings.parts.tab-buttons.pricing')
            @include('admin.settings.parts.tab-buttons.images')
            @include('admin.settings.parts.tab-buttons.smtp-data')
            @include('admin.settings.parts.tab-buttons.location')
        </ul>
    </aside>

    {{-- Content panes --}}
    <div class="settings-content">
        <div class="tab-content p-0">
            @include('admin.settings.parts.tab-forms.main')
            @include('admin.settings.parts.tab-forms.pricing')
            @include('admin.settings.parts.tab-forms.images')
            @include('admin.settings.parts.tab-forms.smtp-data')
            @include('admin.settings.parts.tab-forms.location')
        </div>
    </div>
</div>

@endsection

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/validation/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('style/admin/js/single-upload.js') }}"></script>
    <script src="{{ asset('style/admin/js/multi-upload.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/submit-form.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/handel-error.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-block.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-un-authorize.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/settings-tab-persistence.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/map-location.js') }}"></script>
@endpush
