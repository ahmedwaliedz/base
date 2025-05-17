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
        @include('admin.settings.parts.tab-forms.notification')
        @include('admin.settings.parts.tab-forms.images')
    </div>
</div>
@endsection

@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('style/admin/js/single-upload.js')}}"></script>
    @include('admin.shared.js.submit-form-js')
@endpush
