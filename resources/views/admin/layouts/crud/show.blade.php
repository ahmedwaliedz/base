@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content')
    @if ($model->deleted_at)
        <div class="card mb-2" style="background-color: #96111c5b; border-color: #f5c6cb;">
            <div class="card-body text-center">
                <div class="row ">

                    <div>
                        <i class="fa fa-trash"></i> {{ __('admin/main.deleted') }}
                    </div>

                </div>
            </div>
        </div>
    @endif

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
    <script src="{{ asset('style/admin/custom-js/delete.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/restore.js') }}"></script>
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/submit-form.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/handel-error.js') }}"></script>
@endpush
