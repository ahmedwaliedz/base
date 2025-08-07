@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/css/filter.css')}}"/>
@endpush
@section('content')
    <x-table.buttons
        createRoute="{{ route('admin.roles.create') }}"
        :hasReload="true"
        :hasFilter="true"
    >
    </x-table.buttons>
    <x-table.filter
        :mainCol="'col-md-3'"
        :hasStartDate="true"
        :hasEndDate="true"
        :hasOrderBy="true"
        :filters="[
                [
                    'type' => 'text',
                    'name' => 'name'
                ],
            ]"
    >
    </x-table.filter>
    <div class="row g-4 append-page-content mt-1">

    </div>
@endsection
@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    @include('admin.shared.js.delete-row')
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>
    <script>
        window.translations = {
            retry: "{{ __('admin/main.retry') }}",
            error_loading_data: "{{ __('admin/main.error_loading_data') }}",
            lotti: "{{ asset('storage/uploads/settings/fail.json') }}",
            are_you_sure : "{{ __('admin/main.are_you_sure') }}",
            are_you_sure_want_delete : "{{ __('admin/main.are_you_sure_to_delete') }}",
            confirmButtonText : '{{ __('admin/main.confirm') }}' ,
            cancelButtonText : '{{ __('admin/main.cancel') }}',
            deleted_successfully: "{{ __('admin/main.deleted_successfully') }}",
        };
        const loader = `
            <div class="text-center p-5 table-loader">
                <lottie-player src="{{ asset('storage/uploads/settings/Load.json') }}" background="transparent" speed="1" style="width: 350px; height: 350px; margin: 0 auto;" loop autoplay></lottie-player>
            </div>
        `;
    </script>

    <script src="{{asset('style/admin/custom-js/admin-table.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/filter.js')}}"></script>
@endpush
