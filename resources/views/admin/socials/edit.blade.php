@extends('admin.layouts.crud.edit')

@push('content')
<form id="editSocialForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.socials.update', ['social' => $social]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12', 'value' => $social->image]" />
            <x-form.text :options="['name' => 'link', 'label' => 'link', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $social->link]" />
            <x-form.select :options="['name' => 'is_active', 'label' => 'is_active', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $social->is_active, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush