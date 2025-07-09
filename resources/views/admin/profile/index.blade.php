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
        @include('admin.profile.parts.tab-buttons.main-data')
        @include('admin.profile.parts.tab-buttons.change-password')
    </ul>
    <div class="tab-content">
        @include('admin.profile.parts.tab-forms.main-data')
        @include('admin.profile.parts.tab-forms.change-password')
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
    <script src="{{asset('style/admin/custom-js/profile-tab-persistence.js')}}"></script>
@endpush
