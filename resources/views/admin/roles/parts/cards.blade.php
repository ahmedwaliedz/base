@foreach($roles as $key => $role)
    <div class="col-xl-4 col-lg-6 col-md-6 data-rows">
        <div class="card h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                    <h5 class="mb-1 me-2">{{$role->name}}</h5>
                    <div class="d-flex align-items-center gap-2 ">
                        <a href="{{route('admin.roles.edit' , $role->id)}}" class="bg-success text-white custom-icon" ><i class="ti ti-pencil"></i></a>
                        <a href="{{route('admin.roles.show' , $role->id)}}" class="bg-primary text-white custom-icon" ><i class="ti ti-eye"></i></a>
                        <a href="javascript:void(0);" class="bg-danger text-white custom-icon delete-row" data-route="{{route('admin.roles.destroy',$role->id)}}"><i class="ti ti-trash "></i></a>
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
@if($roles->count() == 0)
    <div class="text-center py-5 mt-5 data-rows">
        <div class="d-flex justify-content-center align-items-center flex-column">
            <i class="ti ti-file-off text-secondary mb-2" style="font-size: 3rem;"></i>
            <h5 class="mb-1">{{__('admin/main.no_data_found')}}</h5>
            <p class="text-muted">{{__('admin/main.no_data_description')}}</p>
        </div>
    </div>
@endif
<div class="data-rows">
    {{$roles->links('admin.layouts.pagination')}}
</div>
