@extends('admin.layouts.crud.edit')

@push('content')
<form id="editSliderForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.sliders.update', ['slider' => $slider]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12', 'value' => $slider->image]" />
            <x-form.text :options="['name' => 'link', 'label' => 'link', 'class' => 'col-md-12', 'value' => $slider->link]" />
            <x-form.select :options="['name' => 'is_active', 'label' => 'is_active', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $slider->is_active, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
            <x-form.text :options="['name' => 'title', 'label' => 'title', 'class' => 'col-md-12', 'isMultiLanguage' => true, 'value' => $slider->getTranslationsArray()]" />
            <x-form.text-area :options="['name' => 'description', 'label' => 'description', 'class' => 'col-md-12', 'isMultiLanguage' => true, 'value' => $slider->getTranslationsArray()]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush