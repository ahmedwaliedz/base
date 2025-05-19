@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/validation/form-validation.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/select2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.css')}}"/>
@endpush
@section('content')
    <div class="card h-100">
        <div class="row h-100">
            <form class="mb-3 validated-form form card-body" action="{{route('admin.roles.store')}}" method="POST" novalidate>
                @csrf
                <div class="row g-3">
                    <x-form.text name="name"  class="col-md-6"  :is-required="true" is-multi-language="true"   />
                    <div class=" w-100 d-flex justify-content-between align-items-center">
                        <div class="divider w-75 align-self-center">
                            <div class="divider-text">{{__('admin/main.permissions')}}</div>
                        </div>

                        <div class=" ">
                            <button  type="button"  class="btn btn-primary waves-effect btn-sm  select-all"    >
                                {{ __('admin/main.select_all') }}
                            </button>
                            <button type="button" class="btn btn-danger waves-effect  btn-sm unselect-all" >
                                {{ __('admin/main.unselect_all') }}
                            </button>
                        </div>
                    </div>

                    {!! $html !!}
                </div>
                <div class="pt-4 d-flex justify-content-center mt-3">
                    <button type="submit"   class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.add') }}</button>
                    <a class="btn btn-label-dribbble waves-effect" href="{{ url()->previous() }}">{{ __('admin/main.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>
    <script src="{{asset('style/admin/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('style/admin/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('style/admin/js/forms-selects.js')}}"></script>
    <script src="{{asset('style/admin/js/select-unselect-all.js')}}"></script>
@endpush
