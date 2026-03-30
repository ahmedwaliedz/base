@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/filter.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/crud-stats.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/apex-charts/apex-charts.css') }}" />
@endpush

@section('content')

    @stack('content')

@endsection

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/filter.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/admin-table.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/delete.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/restore.js') }}"></script>
    <script src="{{ asset('style/admin/validation/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/submit-form.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/handel-error.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-block.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-un-authorize.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-unknown-error.js') }}"></script>
@endpush
