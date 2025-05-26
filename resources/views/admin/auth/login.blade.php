@extends('admin.layouts.auth-master')

@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/css/pages/page-auth.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/validation/form-validation.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
@endpush

@section('content')
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            @include('admin.auth.parts.page-cover-div')
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    @include('admin.auth.parts.form-header')
                    @include('admin.auth.parts.form')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>
@endpush
