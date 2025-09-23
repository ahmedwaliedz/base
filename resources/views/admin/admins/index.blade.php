@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/filter.css') }}" />
@endpush

@section('content')
    <x-table.buttons createRoute="{{ route('admin.admins.create') }}" :hasNotification="true" :hasDeleteAll="true" :deleteAllRoute="route('admin.admins.destroyAll')"
        :hasEmail="true" :hasReload="true" :hasFilter="true" :hasExport="true" :exportPdf="true" :exportExcel="true"
        :exportCopy="true" :hasPagination="true" :perPage="20">
    </x-table.buttons>

    <x-table.filter :mainCol="'col-md-3'" :hasStartDate="true" :hasEndDate="true" :hasOrderBy="true" :filters="[
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

    <x-table.table :hasCheckbox="true" :hasActions="true" :rows="$admins" :headers="[__('admin/main.name'), __('admin/main.role'), __('admin/main.status')]">
    </x-table.table>

    
    <x-model.notification :route="route('admin.notifications.sendNotifications')" :class="'App\Models\Admin'">
    </x-model.notification>

    <x-model.email>
    </x-model.email>
    
@endsection

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/filter.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/admin-table.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/delete.js') }}"></script>
    <script src="{{ asset('style/admin/validation/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/submit-form.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/handel-error.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-block.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-un-authorize.js') }}"></script>
@endpush
