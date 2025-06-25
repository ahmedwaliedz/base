@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/css/filter.css')}}"/>
@endpush
@section('content')
    <div class="card">
        <div class="card-header">
            <x-table.buttons
                createRoute="{{ route('admin.admins.create') }}"
                :hasNotification="true"
                :hasEmail="true"
                :hasDelete="true"
                :hasReload="true"
                :hasFilter="true"
                :hasExport="true"
                :exportPdf="true"
                :exportExcel="true"
                :exportCopy="true"
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
                    [
                        'type' => 'text',
                        'name' => 'phone'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'email'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'options' => [
                            ['id' => 'active', 'name' => __('admin/main.available')],
                            ['id' => 'blocked', 'name' => __('admin/main.blocked')],
                        ]
                    ],
                    [
                        'type' => 'select',
                        'name' => 'role_id',
                        'options' => $roles->map(function($role) {
                            return ['id' => $role->id, 'name' => $role->name];
                        })->toArray()
                    ]
                ]"
            >
            </x-table.filter>
        </div>
        <div id="table-container" style="min-height: 400px;">
            <div class="card-datatable table-responsive table-content">

            </div>
        </div>

    </div>
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-4">
                        <h3 class="address-title mb-2">ارسال اشعار</h3>
                        <!-- <p class="text-muted address-subtitle">من فضلك ادخل رسالتك</p> -->
                    </div>
                    <form id="addNewAddressForm" class="row g-3" onsubmit="return false">

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddressLastName">الرسالة بالعربية</label>
                            <textarea name="" class="form-control" id="" cols="30" rows="5" style="resize: none;"></textarea>
                        </div>

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddressLastName">الرسالة بالانجليزية</label>
                            <textarea name="" class="form-control" id="" cols="30" rows="5" style="resize: none;"></textarea>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa fa-check-double me-1"></i>ارسال</button>
                            <button type="reset" class="btn btn-label-danger me-sm-3 me-1" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-x me-1"> </i>الغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/filter.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/admin-table.js')}}"></script>
    <script>
        const deleteAllRoute = "{{ route('admin.admins.destroyAll') }}";
        const loader = `
            <div class="text-center p-5 table-loader">
                <lottie-player src="{{ asset('storage/uploads/settings/Load.json') }}" background="transparent" speed="1" style="width: 350px; height: 350px; margin: 0 auto;" loop autoplay></lottie-player>
            </div>
        `;

        window.translations = {
            error_loading_data: "{{ __('admin/main.error_loading_data') }}",
            retry: "{{ __('admin/main.retry') }}",
            lotti: "{{ asset('storage/uploads/settings/fail.json') }}",
            start_date_error: "{{ __('admin/main.start_date_greater_than_end_date') }}",
            end_date_error: "{{ __('admin/main.end_date_smaller_than_start_date') }}",
            are_you_sure : "{{ __('admin/main.are_you_sure') }}",
            are_you_sure_want_delete : "{{ __('admin/main.are_you_sure_to_delete') }}",
            confirmButtonText : '{{ __('admin/main.confirm') }}' ,
            cancelButtonText : '{{ __('admin/main.cancel') }}',
            deleted_successfully: "{{ __('admin/main.deleted_successfully') }}",
        };
    </script>
    <script src="{{asset('style/admin/custom-js/delete.js')}}"></script>
@endpush
