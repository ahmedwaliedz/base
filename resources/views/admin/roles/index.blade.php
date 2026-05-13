@extends('admin.layouts.crud.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/roles.css') }}">
@endpush

@push('content')
    <x-table.statistics :loaderCards="6" />

    <x-table.buttons
        createRoute="{{ route('admin.roles.create') }}"
        :hasReload="true"
        :hasFilter="true"
        :hasPagination="true"
        :perPage="9"
    >
    </x-table.buttons>

    <x-table.filter
        :mainCol="'col-md-3'"
        :hasStartDate="true"
        :hasEndDate="true"
        :hasOrderBy="true"
        :filters="[
            ['type' => 'text', 'name' => 'name'],
        ]"
    >
    </x-table.filter>

    <div class="row g-4 append-page-content mt-1">
        @include('admin.roles.parts.loader')
    </div>
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.roles.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
@endpush
