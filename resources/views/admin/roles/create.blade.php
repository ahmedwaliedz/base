@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/validation/form-validation.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/select2/select2.css')}}"/>
@endpush
@section('content')
    <div class="card h-100">
        <div class="row h-100">
            <form class="mb-3 validated-form form card-body" action="{{route('admin.roles.store')}}" method="POST" novalidate>
                @csrf
                <div class="row g-3">
                    <x-form.text name="name"  class="col-md-6"  :is-required="true" is-multi-language="true"   />
                    <div class="m-4 w-100 d-flex justify-content-center">
                        <div class="divider w-75 align-self-center">
                            <div class="divider-text">{{__('admin/main.permissions')}}</div>
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
    <script src="{{asset('style/admin/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>
    <script>
        $('.select2').select2({
            placeholder: "{{ __('admin/main.select_permissions') }}",
            allowClear: true,
            width: '100%',
        });
        // Select All
        $(document).on('click', '.select-all', function() {
            const target = $(this).data('target');
            $(target).find('option').prop('selected', true);
            $(target).trigger('change');
        });

        // Unselect All
        $(document).on('click', '.unselect-all', function() {
            const target = $(this).data('target');
            $(target).val(null).trigger('change');
        });
    </script>
@endpush
