@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/validation/form-validation.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/css/custom-select.css')}}"/>
@endpush
@section('content')
    <div class="card h-100">
        <div class="row h-100">
            @include('admin.roles.parts.buttons')
            @include('admin.roles.parts.loader')
            <div class="append-form">
              {{-- form  will append here --}}
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>
    <script src="{{asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
    <script>
        var formRoute = "{{ route('admin.roles.getForm' , [ 'id' => $role->id ]) }}"
    </script>
    <script src="{{asset('style/admin/js/select-unselect-all.js')}}"></script>
@endpush
