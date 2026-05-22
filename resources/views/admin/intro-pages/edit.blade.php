@extends('admin.layouts.crud.edit')

@push('content')
<form id="editIntroPageForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.intro-pages.update', ['intro_page' =>     $intropage]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => __('admin/main.image'), 'class' => 'col-md-12', 'value' =>     $intropage->image]" />
            <x-form.text :options="['name' => 'link', 'label' => __('admin/main.link'), 'class' => 'col-md-12', 'value' =>     $intropage->link]" />
            <x-form.select :options="['name' => 'is_active', 'label' => __('admin/main.is_active'), 'class' => 'col-md-12', 'isRequired' => true, 'value' =>     $intropage->is_active, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
            <x-form.text :options="['name' => 'title', 'label' => __('admin/main.title'), 'class' => 'col-md-12', 'isMultiLanguage' => true, 'value' =>     $intropage->title]" />
            <x-form.text-area :options="['name' => 'description', 'label' => __('admin/main.description'), 'class' => 'col-md-12', 'isMultiLanguage' => true, 'value' =>     $intropage->description]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush