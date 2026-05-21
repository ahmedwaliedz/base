@extends('admin.layouts.crud.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/pages.css') }}">
@endpush

@push('content')
    <x-table.buttons
        createRoute="{{ route('admin.pages.create') }}"
        :hasNotification="false"
        :hasDeleteAll="true"
        :deleteAllRoute="route('admin.pages.destroyAll')"
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
        :hasRetrieve="$is_retrievable"
        :filters="[
            ['type' => 'text', 'name' => 'slug'],
            ['type' => 'select', 'name' => 'type', 'options' => [
                ['id' => '', 'name' => __('admin/main.all')],
                ['id' => 'user', 'name' => __('admin/main.user')],
                ['id' => 'provider', 'name' => __('admin/main.provider')],
                ['id' => 'public', 'name' => __('admin/main.public')],
            ]],
        ]"
    />

    <x-table.bulk-actions
        :hasDelete="true"
        :deleteRoute="route('admin.pages.destroyAll')"
    />

    <x-table.table
        :hasCheckbox="true"
        :hasActions="true"
        :headers="[
            __('admin/main.title'),
            __('admin/main.slug'),
            __('admin/main.type'),
            __('admin/main.actions'),
        ]"
    />
@endpush

@push('js')
    <script>
        var statsUrl = "";
    </script>
@endpush