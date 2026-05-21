@extends('admin.layouts.crud.create')

@push('content')
<form id="createSliderForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => __('admin/main.image'), 'class' => 'col-md-12']" />
            <x-form.text :options="['name' => 'link', 'label' => __('admin/main.link'), 'class' => 'col-md-12']" />
            <x-form.select :options="['name' => 'is_active', 'label' => __('admin/main.is_active'), 'class' => 'col-md-12', 'isRequired' => true, 'value' => 1, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
            <x-form.text :options="['name' => 'title', 'label' => __('admin/main.title'), 'class' => 'col-md-12', 'isMultiLanguage' => true]" />
            <x-form.text-area :options="['name' => 'description', 'label' => __('admin/main.description'), 'class' => 'col-md-12', 'isMultiLanguage' => true]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.create') }}</button></div>
</form>
@endpush