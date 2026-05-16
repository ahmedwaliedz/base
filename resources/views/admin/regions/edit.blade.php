@extends('admin.layouts.crud.edit')

@push('content')
<form id="editRegionForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.regions.update', ['region' => $region]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.select :options="['name' => 'country_id', 'label' => 'country', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $region->country_id, 'options' => App\Models\Country::get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])]" />
            <x-form.select :options="['name' => 'is_active', 'label' => 'is_active', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $region->is_active, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
        </div>
    </div>
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.text :options="['name' => 'name', 'label' => 'name', 'class' => 'col-md-12', 'isRequired' => true, 'isMultiLanguage' => true, 'value' => $region->name]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush