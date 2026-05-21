@extends('admin.layouts.crud.create')

@push('content')
<form id="createSeoForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.seo.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => __('admin/main.image'), 'class' => 'col-md-12']" />
            <x-form.text :options="['name' => 'meta_title', 'label' => __('admin/main.meta_title'), 'class' => 'col-md-12', 'isMultiLanguage' => true]" />
            <x-form.text-area :options="['name' => 'meta_description', 'label' => __('admin/main.meta_description'), 'class' => 'col-md-12', 'isMultiLanguage' => true]" />
            <x-form.text :options="['name' => 'meta_keywords', 'label' => __('admin/main.meta_keywords'), 'class' => 'col-md-12', 'isMultiLanguage' => true]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.create') }}</button></div>
</form>
@endpush