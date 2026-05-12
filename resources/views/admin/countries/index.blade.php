@extends('admin.layouts.crud.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/countries.css') }}">
@endpush

@push('content')
    <x-table.statistics :loaderCards="6" />

    <x-table.buttons
        createRoute="{{ route('admin.countries.create') }}"
        :hasNotification="false"
        :hasDeleteAll="true"
        :deleteAllRoute="route('admin.countries.destroyAll')"
        :hasEmail="false"
        :hasReload="true"
        :hasFilter="true"
        :hasSearch="true"
        :hasExport="true"
        :exportCopy="true"
        :exportPdf="true"
        :exportExcel="true"
        :exportWord="true"
        :exportJson="true"
        :hasPagination="true"
        :perPage="20"
    />

    <x-table.filter
        :mainCol="'col-md-3'"
        :hasStartDate="true"
        :hasEndDate="true"
        :hasOrderBy="true"
        :hasRetrieve="$is_retreivable"
        :filters="[
            ['type' => 'text', 'name' => 'name'],
            ['type' => 'text', 'name' => 'code'],
            [
                'type' => 'select',
                'name' => 'is_active',
                'options' => [
                    ['id' => '', 'name' => __('admin/main.all')],
                    ['id' => 'active_only', 'name' => __('admin/main.active_countries')],
                    ['id' => 'inactive_only', 'name' => __('admin/main.inactive_countries')],
                ],
            ],
        ]"
    />

    <x-table.bulk-actions
        :hasDelete="true"
        :deleteRoute="route('admin.countries.destroyAll')"
    />

    <x-table.table
        :hasCheckbox="true"
        :hasActions="true"
        :headers="[
            __('admin/main.name'),
            __('admin/main.code'),
            __('admin/main.regions'),
            __('admin/main.cities'),
            __('admin/main.status'),
        ]"
    />
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.countries.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
@endpush
