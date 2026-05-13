@extends('admin.layouts.crud.create')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/countries.css') }}">
@endpush

@push('content')
    <form id="createCountryForm" class="mb-3 validated-form form" novalidate method="POST"
          action="{{ route('admin.countries.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-flag"></i>
                <span>{{ __('admin/main.country_details') }}</span>
            </div>
            <div class="row g-3">
                <x-form.image :options="['name' => 'flag', 'label' => 'flag', 'class' => 'col-md-12']" />
                <x-form.text :options="[
                    'name' => 'name',
                    'label' => 'name',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                    'isMultiLanguage' => true,
                ]" />
                <x-form.text :options="[
                    'name' => 'code',
                    'label' => 'code',
                    'class' => 'col-md-6',
                    'isRequired' => true,
                ]" />
                <x-form.select :options="[
                    'name' => 'is_active',
                    'label' => 'is_active',
                    'class' => 'col-md-6',
                    'isRequired' => true,
                    'value' => 1,
                    'options' => [
                        ['id' => 1, 'name' => __('admin/main.yes')],
                        ['id' => 0, 'name' => __('admin/main.no')],
                    ],
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
