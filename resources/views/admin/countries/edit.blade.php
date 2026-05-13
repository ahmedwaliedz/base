@extends('admin.layouts.crud.edit')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/countries.css') }}">
@endpush

@push('content')
    <form class="mb-3 validated-form form" novalidate method="POST"
          action="{{ route('admin.countries.update', $id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-flag"></i>
                <span>{{ __('admin/main.country_details') }}</span>
            </div>
            <div class="row g-3">
                <x-form.image :options="['name' => 'flag', 'label' => 'flag', 'class' => 'col-md-12', 'value' => $country->flag]" />
                <x-form.text :options="[
                    'name' => 'name',
                    'label' => 'name',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                    'isMultiLanguage' => true,
                    'value' => $country->getTranslationsArray('name'),
                ]" />
                <x-form.text :options="[
                    'name' => 'code',
                    'label' => 'code',
                    'class' => 'col-md-6',
                    'isRequired' => true,
                    'value' => $country->code,
                ]" />
                <x-form.select :options="[
                    'name' => 'is_active',
                    'label' => 'is_active',
                    'class' => 'col-md-6',
                    'isRequired' => true,
                    'value' => $country->is_active ? 1 : 0,
                    'options' => [
                        ['id' => 1, 'name' => __('admin/main.yes')],
                        ['id' => 0, 'name' => __('admin/main.no')],
                    ],
                ]" />
            </div>
        </div>

        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-success me-sm-3 me-1 waves-effect waves-light submit-button">
                <i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.edit') }}
            </button>
        </div>
    </form>
@endpush
