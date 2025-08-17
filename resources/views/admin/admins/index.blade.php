@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/css/filter.css')}}"/>
@endpush
@section('content')
    <x-table.buttons
        createRoute="{{ route('admin.admins.create') }}"
        :hasNotification="true"
        :hasDeleteAll="true"
        :deleteAllRoute="route('admin.admins.destroyAll')"
        :hasEmail="true"
        :hasReload="true"
        :hasFilter="true"
        :hasExport="true"
        :exportPdf="true"
        :exportExcel="true"
        :exportCopy="true"
        :hasPagination="true"
        :perPage="20"
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

    <x-table.table :hasCheckbox="true" :hasActions="true" :rows="$admins" :headers="[
            __('admin/main.name'),
            __('admin/main.role'),
            __('admin/main.status'),
        ]"
    >


    </x-table.table>

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
                            <textarea name="" class="form-control" id="" cols="30" rows="5"
                                      style="resize: none;"></textarea>
                        </div>

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddressLastName">الرسالة بالانجليزية</label>
                            <textarea name="" class="form-control" id="" cols="30" rows="5"
                                      style="resize: none;"></textarea>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1"><i
                                    class="fa fa-check-double me-1"></i>ارسال
                            </button>
                            <button type="reset" class="btn btn-label-danger me-sm-3 me-1" data-bs-dismiss="modal"
                                    aria-label="Close">
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
    <script src="{{asset('style/admin/custom-js/delete.js')}}"></script>
@endpush
