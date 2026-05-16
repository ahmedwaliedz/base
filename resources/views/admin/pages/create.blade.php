@extends('admin.layouts.crud.create')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/pages.css') }}">
@endpush

@push('content')
    <form id="createPageForm" class="mb-3 validated-form form" novalidate method="POST"
          action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-file-text"></i>
                <span>{{ __('admin/main.page_details') }}</span>
            </div>
            <div class="row g-3">
                <x-form.text :options="[
                    'name' => 'slug',
                    'label' => 'slug',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                ]" />
                <x-form.text :options="[
                    'name' => 'icon',
                    'label' => 'icon',
                    'class' => 'col-md-12',
                    'isRequired' => false,
                ]" />
                <x-form.select :options="[
                    'name' => 'type',
                    'label' => 'type',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                    'options' => [
                        ['id' => 'user', 'name' => __('admin/main.user')],
                        ['id' => 'provider', 'name' => __('admin/main.provider')],
                        ['id' => 'public', 'name' => __('admin/main.public')],
                    ],
                ]" />
            </div>
        </div>

        <div class="admins-form-section">
            <div class="admins-form-section__head">
                <i class="ti ti-language"></i>
                <span>{{ __('admin/main.translations') }}</span>
            </div>
            <div class="row g-3">
                <x-form.text :options="[
                    'name' => 'title',
                    'label' => 'title',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                    'isMultiLanguage' => true,
                ]" />
                <x-form.text-area :options="[
                    'name' => 'content',
                    'label' => 'content',
                    'class' => 'col-md-12',
                    'isRequired' => true,
                    'isMultiLanguage' => true,
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