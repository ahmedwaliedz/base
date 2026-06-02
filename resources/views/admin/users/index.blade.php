@extends('admin.layouts.crud.index')

@push('css')
    <style>
        .users-table-scroll .card-datatable.table-responsive {
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
        }

        .users-table-scroll .datatables-products.table {
            min-width: 64rem;
            width: 100%;
        }

        .users-table-scroll .users-table-row {
            z-index: auto;
        }
    </style>
@endpush

@push('content')
    <x-table.statistics :loaderCards="6" />

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
                     :exportJson="true"
                     :hasPagination="true"
                     :perPage="20" />

    <x-table.filter :mainCol="'col-md-3'"
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
                            'name' => 'is_blocked',
                            'options' => [
                                ['id' => '', 'name' => __('admin/main.all')],
                                ['id' => 'not_blocked', 'name' => __('admin/main.active')],
                                ['id' => 'blocked_only', 'name' => __('admin/main.blocked')],
                            ],
                        ],
                    ]" />

    <x-table.bulk-actions :hasDelete="true"
                          :deleteRoute="route('admin.users.destroyAll')" />

    <div class="users-table-scroll">
        <x-table.table :hasCheckbox="true"
                       :hasActions="true"
                       :headers="[
                           __('admin/main.name'),
                           __('admin/main.phone'),
                           __('admin/main.email'),
                           __('admin/main.status'),
                       ]" />
    </div>

    <x-model.notification :route="route('admin.notifications.sendNotifications')"
                          :class="'App\Models\User'" />

    <x-model.email :class="'App\Models\User'" />
@endpush

@push('js')
    <script>
        var statsUrl = "{{ route('admin.users.statistics') }}";
    </script>
    <script src="{{ asset('style/admin/custom-js/stats.js') }}"></script>
@endpush
