@extends('admin.layouts.crud.edit')

@push('content')
    <form class="user-form validated-form form mb-3" novalidate method="POST" action="{{ route('admin.users.update', $id) }}"
        enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <fieldset class="form-section">
            <legend class="form-section__legend">
                <span class="form-section__icon"><i class="ti ti-user-circle"></i></span>
                <span class="form-section__title">{{ __('admin/main.section_profile') }}</span>
                <span class="form-section__hint">{{ __('admin/main.section_profile_hint') }}</span>
            </legend>
            <div class="row g-3">
                <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12', 'value' => $user->image]" />
                <x-form.text :options="[
                    'name' => 'name',
                    'label' => 'name',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                    'value' => $user->name,
                ]" />
            </div>
        </fieldset>

        <fieldset class="form-section">
            <legend class="form-section__legend">
                <span class="form-section__icon"><i class="ti ti-address-book"></i></span>
                <span class="form-section__title">{{ __('admin/main.section_contact') }}</span>
                <span class="form-section__hint">{{ __('admin/main.section_contact_hint') }}</span>
            </legend>
            <div class="row g-3">
                <x-form.email :options="[
                    'name' => 'email',
                    'label' => 'email',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                    'value' => $user->email,
                ]" />
                <x-form.number :options="[
                    'name' => 'phone',
                    'label' => 'phone',
                    'class' => 'col-md-8',
                    'isRequired' => true,
                    'minLength' => 9,
                    'maxLength' => 15,
                    'value' => $user->phone,
                ]" />
                <x-form.select :options="[
                    'name' => 'country_code',
                    'label' => 'country_code',
                    'class' => 'col-md-4',
                    'isRequired' => true,
                    'options' => $countries,
                    'value' => $user->country_code,
                ]" />
            </div>
        </fieldset>

        <fieldset class="form-section">
            <legend class="form-section__legend">
                <span class="form-section__icon"><i class="ti ti-shield-lock"></i></span>
                <span class="form-section__title">{{ __('admin/main.section_account') }}</span>
                <span class="form-section__hint">{{ __('admin/main.section_account_hint') }}</span>
            </legend>
            <div class="row g-3">
                <x-form.password :options="['name' => 'password', 'label' => 'password', 'class' => 'col-md-12', 'isRequired' => false]" />
            </div>
        </fieldset>

        <fieldset class="form-section">
            <legend class="form-section__legend">
                <span class="form-section__icon"><i class="ti ti-adjustments"></i></span>
                <span class="form-section__title">{{ __('admin/main.section_preferences') }}</span>
                <span class="form-section__hint">{{ __('admin/main.section_preferences_hint') }}</span>
            </legend>
            <div class="row g-3">
                <x-form.select :options="[
                    'name' => 'is_notify',
                    'value' => true,
                    'label' => 'receive_notifications',
                    'class' => 'col-md-12',
                    'options' => $receiveNotificationsOptions,
                    'value' => $user->is_notify,
                ]" />
            </div>
        </fieldset>

        <div class="user-form__actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary">
                <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.cancel') }}
            </a>
            <button type="submit" class="btn btn-success submit-button waves-effect waves-light">
                <i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.edit') }}
            </button>
        </div>
    </form>
@endpush
