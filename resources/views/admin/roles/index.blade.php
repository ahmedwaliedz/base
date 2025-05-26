@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
@endpush
@section('content')
    <div class="row g-4">
        @foreach($roles as $key => $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-normal mb-2">{{__('admin/main.total_admin_um' , ['num' => $role->admins()->count()])}} </h6>
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                @foreach($role->admins as $admin)
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="{{$admin->name}}" data-bs-original-title="{{$admin->name}}">
                                        <img class="rounded-circle" src="{{$admin->image_url}}" alt="{{$admin->name}}" >
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-1">
                            <div class="role-heading">
                                <h4 class="mb-1">{{$role->name}}</h4>
                                <a href="{{route('admin.roles.edit' ,  $role->id)}}"  class="role-edit-modal"><span>{{__('admin/main.edit_role')}}</span></a>
                            </div>
                            <div>
                                <a href="javascript:void(0);" class="text-danger delete-row" data-route="{{route('admin.admins.destroy',$role->id)}}"><i class="ti ti-trash-x ti-md"></i></a>
                                <a href="{{route('admin.roles.show' , $role->id)}}" class="text-success" ><i class="ti ti-eye-check ti-md"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-5">
                        <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                            <img src="{{asset('style/admin/img/illustrations/add-new-roles.png')}}" class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83">
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <a href="{{route('admin.roles.create')}}"  class="btn btn-primary mb-2 text-nowrap  waves-effect waves-light">
                                {{__('admin/main.add_new_role')}}
                            </a>
{{--                            <p class="mb-0 mt-1">Add role, if it does not exist</p>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    @include('admin.shared.js.delete-row')
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>
@endpush
