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
                [
                    'type' => 'text',
                    'name' => 'name'
                ],
            ]"
    >
    </x-table.filter>

    <div class="row g-4 append-page-content mt-1">

        <div class="text-center p-5 table-loader" >
            <lottie-player src="{{ asset('storage/uploads/settings/Load.json') }}" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></lottie-player>
        </div>

    </div>
@endsection
@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/filter.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/admin-table.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/delete.js')}}"></script>
@endpush
