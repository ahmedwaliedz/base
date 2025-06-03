@extends('admin.layouts.master')
@push('css')
@endpush
@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <span class="fw-medium mx-2 text-heading"> {{__('admin/main.name_ar')}}:</span> <span>{{$role->translate('ar')->name}}</span>
                </div>
                <div class="col-md-6">
                    <span class="fw-medium mx-2 text-heading"> {{__('admin/main.name_en')}} :</span> <span>{{$role->translate('en')->name}}</span>
                </div>
            </div>
            <div class="w-100 d-flex justify-content-center mb-4">
                <div class="divider w-75 align-self-center">
                    <div class="divider-text">
                        {{__('admin/main.permissions')}}
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($role->permissions as $permission)
                    <div class="col-md-6 mb-4">
                        <span class="fw-medium mx-2 text-heading d-block">الرئيسية:</span>
                        <ul class="list-unstyled mb-4 mt-3">

                            <span class="badge bg-primary" >مدير مشروع</span>

                            <span class="badge bg-primary" >مدير مشروع</span>

                            <span class="badge bg-primary" >مدير مشروع</span>
                        </ul>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection
@push('js')
    @include('admin.shared.js.submit-form-js')
@endpush
