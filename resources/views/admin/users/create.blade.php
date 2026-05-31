@extends('admin.layouts.crud.create')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('content')
    <form id="createUserForm" class="mb-3 validated-form form" novalidate method="POST"
          action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-user-circle"></i>
                <span>{{ __('admin/main.section_profile') }}</span>
            </div>
            <div class="row g-3">
                <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12']" />
                <x-form.text :options="['name' => 'name', 'label' => 'name', 'class' => 'col-md-6', 'isRequired' => true]" />
                <x-form.email :options="['name' => 'email', 'label' => 'email', 'class' => 'col-md-6', 'isRequired' => true]" />
            </div>
        </div>

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-address-book"></i>
                <span>{{ __('admin/main.section_contact') }}</span>
            </div>
            <div class="row g-3">
                <x-form.number :options="[
                    'name' => 'phone',
                    'label' => 'phone',
                    'class' => 'col-md-6',
                    'isRequired' => true,
                    'minLength' => 9,
                    'maxLength' => 15,
                ]" />
                <x-form.select :options="[
                    'name' => 'country_code',
                    'label' => 'country_code',
                    'class' => 'col-md-6',
                    'isRequired' => true,
                    'options' => $countries,
                ]" />
            </div>
        </div>

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-lock"></i>
                <span>{{ __('admin/main.section_account') }}</span>
            </div>
            <div class="row g-3">
                <x-form.password :options="[
                    'name' => 'password',
                    'label' => 'password',
                    'class' => 'col-md-6',
                    'isRequired' => true,
                ]" />
            </div>
        </div>

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-adjustments"></i>
                <span>{{ __('admin/main.section_preferences') }}</span>
            </div>
            <div class="row g-3">
                <x-form.select :options="[
                    'name' => 'is_notify',
                    'value' => true,
                    'label' => 'receive_notifications',
                    'class' => 'col-md-6',
                    'options' => $receiveNotificationsOptions,
                ]" />
            </div>
        </div>

        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">
                <i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.create') }}
            </button>
        </div>
    </form>
@endpush
