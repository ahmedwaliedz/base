@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/single-upload.css') }}" />
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            
            @stack('content')

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/validation/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('style/admin/js/single-upload.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/submit-form.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/handel-error.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-block.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-un-authorize.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-unknown-error.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Function to handle role_id visibility and required attribute based on type selection
            function handleRoleVisibility() {
                const typeValue = $('select[name="type"]').val();
                const roleContainer = $('select[name="role_id"]').parents('.form-group');
                const roleSelect = $('select[name="role_id"]');

                if (typeValue === 'super_admin') {
                    roleContainer.fadeOut();
                    roleSelect.prop('required', false);
                } else {
                    roleContainer.fadeIn();
                    roleSelect.prop('required', true);
                }
            }

            // Initial check on page load
            handleRoleVisibility();

            // Add event listener for type changes
            $('select[name="type"]').on('change', function() {
                handleRoleVisibility();
            });
        });
    </script>
@endpush
