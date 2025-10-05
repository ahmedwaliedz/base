@extends('admin.layouts.crud.index')
@push('content')
    <x-table.buttons createRoute="{{ route('admin.admins.create') }}" :hasNotification="true" :hasDeleteAll="true" :deleteAllRoute="route('admin.admins.destroyAll')"
        :hasEmail="true" :hasReload="true" :hasFilter="true" :hasExport="true" :exportPdf="true" :exportExcel="true"
        :exportCopy="true" :hasPagination="true" :perPage="20">
    </x-table.buttons>

    <x-table.filter :mainCol="'col-md-3'" :hasStartDate="true" :hasEndDate="true" :hasOrderBy="true" :hasRetrieve="$is_retreivable"
        :filters="[
            [
                'type' => 'text',
                'name' => 'name',
            ],
            [
                'type' => 'text',
                'name' => 'phone',
            ],
            [
                'type' => 'text',
                'name' => 'email',
            ],
            [
                'type' => 'select',
                'name' => 'status',
                'options' => [
                    ['id' => 'active', 'name' => __('admin/main.available')],
                    ['id' => 'blocked', 'name' => __('admin/main.blocked')],
                ],
            ],
            [
                'type' => 'select',
                'name' => 'role_id',
                'options' => $roles,
            ],
        ]">

    </x-table.filter>

    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.name'), __('admin/main.role'), __('admin/main.status')]">
    </x-table.table>


    <x-model.notification :route="route('admin.notifications.sendNotifications')" :class="'App\Models\Admin'">
    </x-model.notification>

    <x-model.email>
    </x-model.email>
@endpush

@push('js')
@endpush
