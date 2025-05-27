@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <style>
        .custom-icon{
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }
    </style>
@endpush
@section('content')
    <div class="row g-4">
        @foreach($roles as $key => $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                            <h5 class="mb-1 me-2">{{$role->name}}</h5>
                            <div class="d-flex align-items-center gap-2 ">
                                <a href="{{route('admin.roles.edit' , $role->id)}}" class="bg-success text-white custom-icon" ><i class="ti ti-pencil"></i></a>
                                <a href="{{route('admin.roles.show' , $role->id)}}" class="bg-primary text-white custom-icon" ><i class="ti ti-eye"></i></a>
                                <a href="javascript:void(0);" class="bg-danger text-white custom-icon delete-row" data-route="{{route('admin.admins.destroy',$role->id)}}"><i class="ti ti-trash "></i></a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <h6 class="fw-normal mb-1 me-2">{{__('admin/main.total_admin_um' , ['num' => $role->admins()->count()])}} </h6>
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-1">
                                @foreach($role->admins as $admin)
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up" aria-label="{{$admin->name}}" data-bs-original-title="{{$admin->name}}">
                                        <img class="rounded-circle" src="{{$admin->image_url}}" alt="{{$admin->name}}" >
                                    </li>
                                @endforeach
                            </ul>
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
