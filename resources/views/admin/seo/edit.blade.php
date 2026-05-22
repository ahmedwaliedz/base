@extends('admin.layouts.crud.edit')

@push('content')
<form id="editSeoForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.seo.update', ['seo' => $seo]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12', 'value' => $seo->image]" />
            <x-form.text :options="['name' => 'meta_title', 'label' => 'meta_title', 'class' => 'col-md-12', 'isMultiLanguage' => true, 'value' => $seo->getTranslationsArray('meta_title')]" />
            <x-form.text-area :options="['name' => 'meta_description', 'label' => 'meta_description', 'class' => 'col-md-12', 'isMultiLanguage' => true, 'value' => $seo->getTranslationsArray('meta_description')]" />
            <x-form.text :options="['name' => 'meta_keywords', 'label' => 'meta_keywords', 'class' => 'col-md-12', 'isMultiLanguage' => true, 'value' => $seo->getTranslationsArray('meta_keywords')]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush