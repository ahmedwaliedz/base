@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/validation/form-validation.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/select2/select2.css')}}"/>
@endpush
@section('content')
<div class="nav-align-top mb-4">
    <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
        @include('admin.notifications.parts.tab-buttons.notification')
        @include('admin.notifications.parts.tab-buttons.mail')
        @include('admin.notifications.parts.tab-buttons.sms')
    </ul>
    <div class="tab-content">
        @include('admin.notifications.parts.tab-forms.notification')
        @include('admin.notifications.parts.tab-forms.mail')
        @include('admin.notifications.parts.tab-forms.sms')
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
    <script>
        $(function () {
            const $userClassSelect = $('.user-class-select');
            const $notificationTypesSelect = $('.notification-types');

            function updateNotificationTypes() {
                const selectedClass = $userClassSelect.val();

                // Clear current options except the first ("all")
                $notificationTypesSelect.find('option:not(:first)').remove();

                if (selectedClass === 'App\\Models\\User') {
                    @foreach(\App\Models\User::getAvailableNotificationTypes() as $type)
                    $('<option>', {
                        value: "{{ $type->value }}",
                        text:  "{{ $type->label() }}"
                    }).appendTo($notificationTypesSelect);
                    @endforeach
                } else if (selectedClass === 'App\\Models\\Admin') {
                    @foreach(\App\Models\Admin::getAvailableNotificationTypes() as $type)
                    $('<option>', {
                        value: "{{ $type->value }}",
                        text:  "{{ $type->label() }}"
                    }).appendTo($notificationTypesSelect);
                    @endforeach
                }
            }

            $userClassSelect.on('change', updateNotificationTypes);
            updateNotificationTypes(); // init
        });
    </script>

@endpush
