@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/select2/select2.css')}}"/>
@endpush
@section('content')
    <div class="card h-100">
        <div class="row h-100">
            <form class="mb-3 form card-body">
                <div class="row g-3">
                    <x-form.text name="name" :value="$role->getTranslationsArray('name')"  class="col-md-6"  :is-required="true" is-multi-language="true"   />
                    {!! $html !!}
                </div>
                <div class="pt-4 d-flex justify-content-center mt-3">
                    <a class="btn btn-label-dribbble waves-effect" href="{{ url()->previous() }}">{{ __('admin/main.back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('style/admin/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('style/admin/vendor/libs/select2/select2.js')}}"></script>
    @include('admin.shared.js.submit-form-js')
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
    <script>
        $(function(){
            $('.mb-2').remove()
            $('.unselect-all').remove()
            // disable all form controls inside your form
            const $form = $('form.mb-3.form.card-body');

            // disable plain inputs, textareas, buttons…
            $form.find('input, textarea, button').prop('disabled', true);

            // disable your Select2 selects *and* their visible boxes
            $form.find('select.select2').each(function(){
                // disable the original <select>
                $(this).prop('disabled', true);

                // disable the Select2 UI widget
                const data = $(this).data('select2');
                if (data && data.$container) {
                    data.$container.find('.select2-selection').addClass('disabled');
                }
            });
        });
    </script>
@endpush
