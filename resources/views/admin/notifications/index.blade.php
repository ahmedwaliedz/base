@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/settings.css') }}" />
@endpush

@section('content')

{{-- ═══════════════════ PAGE HEADER ═══════════════════ --}}
<div class="settings-page-head">
    <span class="settings-page-head__icon" aria-hidden="true">
        <i class="ti ti-bell"></i>
    </span>
    <div>
        <h1 class="settings-page-head__title">{{ __('admin/main.notifications') }}</h1>
        <p class="settings-page-head__desc">{{ __('admin/main.notif_page_desc') }}</p>
    </div>
</div>

{{-- ═══════════════════ SHELL ═══════════════════ --}}
<div class="settings-shell">
    {{-- Sidebar nav (vertical desktop / horizontal mobile) --}}
    <aside class="settings-nav" aria-label="{{ __('admin/main.notifications') }}">
        <ul class="settings-nav__list" role="tablist">
            @include('admin.notifications.parts.tab-buttons.notification')
            @include('admin.notifications.parts.tab-buttons.mail')
            @include('admin.notifications.parts.tab-buttons.sms')
        </ul>
    </aside>

    {{-- Content panes --}}
    <div class="settings-content">
        <div class="tab-content p-0">
            @include('admin.notifications.parts.tab-forms.notification')
            @include('admin.notifications.parts.tab-forms.mail')
            @include('admin.notifications.parts.tab-forms.sms')
        </div>
    </div>
</div>

@endsection

@push('js')
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/validation/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/submit-form.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/handel-error.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-block.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/error-handlers/show-un-authorize.js') }}"></script>
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
