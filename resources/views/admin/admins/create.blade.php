@extends('admin.layouts.crud.create')

@push('content')
    <form id="createAdminForm" class="mb-3 validated-form form" novalidate method="POST"
        action="{{ route('admin.admins.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12']" />

            <x-form.text :options="['name' => 'name', 'label' => 'name', 'class' => 'col-md-6', 'isRequired' => true]" />
            <x-form.number :options="[
                'name' => 'phone',
                'label' => 'phone',
                'class' => 'col-md-4',
                'isRequired' => true,
                'minLength' => 9,
                'maxLength' => 15,
            ]" />
            <x-form.select :options="[
                'name' => 'country_code',
                'label' => 'country_code',
                'class' => 'col-md-2',
                'isRequired' => true,
                'options' => $countries,
            ]" />
            <x-form.email :options="['name' => 'email', 'label' => 'email', 'class' => 'col-md-6', 'isRequired' => true]" />
            <x-form.password :options="[
                'name' => 'password',
                'label' => 'password',
                'class' => 'col-md-6',
                'isRequired' => true,
            ]" />
            <x-form.select :options="[
                'name' => 'type',
                'label' => 'type',
                'class' => 'col-md-4',
                'isRequired' => true,
                'options' => $types,
            ]" />
            <x-form.select :options="[
                'name' => 'role_id',
                'label' => 'role',
                'class' => 'col-md-4',
                'options' => $roles,
                'isRequired' => true,
            ]" />
            <x-form.select :options="[
                'name' => 'is_notify',
                'value' => true,
                'label' => 'receive_notifications',
                'class' => 'col-md-4',
                'options' => $receiveNotificationsOptions,
            ]" />
        </div>

        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit"
                class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.create') }}</button>
        </div>
    </form>
@endpush
