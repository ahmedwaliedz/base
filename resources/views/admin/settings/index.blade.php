@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/validation/form-validation.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/select2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/css/single-upload.css')}}"/>
@endpush
@section('content')
<div class="nav-align-top mb-4">
    <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
        @include('admin.settings.parts.tab-buttons.main')
        @include('admin.settings.parts.tab-buttons.images')
        @include('admin.settings.parts.tab-buttons.notification')
    </ul>
    <div class="tab-content">
        @include('admin.settings.parts.tab-forms.main')
        @include('admin.settings.parts.tab-forms.images')
        @include('admin.settings.parts.tab-forms.notification')
    </div>
</div>
@endsection

@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('style/admin/js/single-upload.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/settings-tab-persistence.js')}}"></script>
@endpush
