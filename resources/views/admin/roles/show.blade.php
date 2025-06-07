@extends('admin.layouts.master')
@section('content')
    <div class="card mb-4">
        <div class="card-body">


            <div class="d-flex justify-content-end gap-3 mb-5">
                <div class="d-flex align-items-center">
                            <span class="text-white rounded badge-center bg-primary me-2">
                                <i class="ti ti-check"></i>
                            </span>
                    <span>{{__('admin/main.assigned_permissions')}}</span>
                </div>
                <div class="d-flex align-items-center">
                            <span class="text-white rounded badge-center bg-secondary me-2">
                                <i class="ti ti-x"></i>
                            </span>
                    <span>{{__('admin/main.unassigned_permissions')}}</span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <span class="fw-medium mx-2 text-heading"> {{__('admin/main.name_ar')}} :</span> <span>{{$role->translate('ar')->name}}</span>
                </div>
                <div class="col-md-6">
                    <span class="fw-medium mx-2 text-heading"> {{__('admin/main.name_en')}} : </span> <span>{{$role->translate('en')->name}}</span>
                </div>
            </div>

            <div class="w-100 d-flex justify-content-center mb-2">
                <div class="divider w-75 align-self-center">
                    <div class="divider-text">
                        {{__('admin/main.permissions')}}
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($permissionsByGroup as $groupKey => $routes)
                    <div class="col-md-6 mb-4">
                        <span class="fw-medium mx-2 text-heading d-block">{{ \App\Traits\Role\RoleTrait::translateRouteName('admin.' . $groupKey) }}:</span>
                        <ul class="list-unstyled mb-4 mt-3 gap-2 d-flex flex-wrap">
                            @foreach($routes as $route)
                                <span class="mb-2 badge {{ isset($permissions) &&  in_array($route['name'], $permissions) ? 'bg-primary' : 'bg-secondary' }}" >
                                    {{ $route['label'] }}
{{--                                    <i class="ti  {{ isset($permissions) &&  in_array($route['name'], $permissions) ? 'ti-eye-check' : 'ti-eye-x' }}"></i>--}}
                                </span>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
                <div class="pt-4 d-flex justify-content-center mt-3">
                    <a class="btn btn-label-dribbble waves-effect" href="{{ url()->previous() }}">{{ __('admin/main.back') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
