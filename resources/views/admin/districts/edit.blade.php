@extends('admin.layouts.crud.edit')

@push('content')
<form id="editDistrictForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.districts.update', ['district' => $district]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.select :options="['name' => 'city_id', 'label' => 'city', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $district->city_id, 'options' => $cities]" />
            <x-form.select :options="['name' => 'is_active', 'label' => 'is_active', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $district->is_active, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
        </div>
    </div>
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.text :options="['name' => 'name', 'label' => 'name', 'class' => 'col-md-12', 'isRequired' => true, 'isMultiLanguage' => true, 'value' => $district->getTranslationsArray()]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush