@extends('admin.layouts.crud.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('content')
    <x-table.statistics :loaderCards="6" />

    <x-table.buttons
        createRoute="{{ route('admin.admins.create') }}"
        :hasNotification="true"
        :hasDeleteAll="true"
        :deleteAllRoute="route('admin.admins.destroyAll')"
        :hasEmail="true"
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
            ['type' => 'text', 'name' => 'name'],
            ['type' => 'text', 'name' => 'phone'],
            ['type' => 'text', 'name' => 'email'],
            [
                'type' => 'select',
                'name' => 'status',
                'options' => [
                    ['id' => '', 'name' => __('admin/main.all')],
                    ['id' => 'active', 'name' => __('admin/main.available')],
                    ['id' => 'blocked', 'name' => __('admin/main.blocked')],
                ],
            ],
            ['type' => 'select', 'name' => 'role_id', 'options' => $roles],
        ]"
    />

    <x-table.bulk-actions
        :hasDelete="true"
        :deleteRoute="route('admin.admins.destroyAll')"
    />

    <div class="admins-table-scroll">
        <x-table.table
            :hasCheckbox="true"
            :hasActions="true"
            :headers="[
                __('admin/main.id'),
                __('admin/main.name'),
                __('admin/main.email'),
                __('admin/main.phone'),
                __('admin/main.country_code'),
                __('admin/main.role'),
                __('admin/main.is_notify'),
                __('admin/main.deleted_at'),
                __('admin/main.status'),
            ]"
        />
    </div>

    <x-model.notification
        :route="route('admin.notifications.sendNotifications')"
        :class="'App\Models\Admin'"
    />
    <x-model.email :class="'App\Models\Admin'" />
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.admins.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
@endpush
