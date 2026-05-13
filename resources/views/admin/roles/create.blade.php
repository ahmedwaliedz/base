@extends('admin.layouts.crud.create')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/roles.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/custom-select.css') }}">
@endpush

@push('content')
    @include('admin.roles.parts.buttons')
    @include('admin.roles.parts.loader-form')
    <div class="append-form">
        {{-- form will be injected by select-unselect-all.js via formRoute --}}
    </div>
@endpush

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script>
        var formRoute = "{{ route('admin.roles.getForm') }}";
    </script>
    <script src="{{ asset('style/admin/js/select-unselect-all.js') }}"></script>
@endpush
