@extends('admin.layouts.crud.index')

@push('content')
    <x-table.statistics :loaderCards="4" />

    <x-table.buttons createRoute="{{ route('admin.users.create') }}"
                     :hasNotification="true"
                     :hasDeleteAll="true"
                     :deleteAllRoute="route('admin.users.destroyAll')"
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
                     :perPage="20" />

    <x-table.filter :mainCol="'col-md-3'"
                    :hasStartDate="true"
                    :hasEndDate="true"
                    :hasOrderBy="true"
                    :hasRetrieve="$is_retreivable"
                    :filters="[
                        ['type' => 'text', 'name' => 'name'],
                        ['type' => 'text', 'name' => 'phone'],
                        ['type' => 'text', 'name' => 'email'],
                    ]" />

    <x-table.bulk-actions :hasDelete="true"
                          :deleteRoute="route('admin.users.destroyAll')" />

    <x-table.table :hasCheckbox="true"
                   :hasActions="true"
                   :headers="[
                       __('admin/main.name'),
                       __('admin/main.phone'),
                       __('admin/main.email'),
                       __('admin/main.status'),
                   ]" />

    <x-model.notification :route="route('admin.notifications.sendNotifications')"
                          :class="'App\Models\User'" />

    <x-model.email />
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.users.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
@endpush
