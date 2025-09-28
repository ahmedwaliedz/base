@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @stack('header')
        </div>
        <div class="card-body">
            <div class="row">
                
                @stack('content')   

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/submit-form.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/handel-error.js') }}"></script>
@endpush
