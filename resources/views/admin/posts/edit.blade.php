@extends('admin.layouts.crud.edit')

@push('content')
<form id="editPostForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.posts.update', ['post' => $post]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12', 'value' => $post->image]" />
            <x-form.select :options="['name' => 'is_active', 'label' => 'is_active', 'class' => 'col-md-12', 'isRequired' => true, 'value' => $post->is_active, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
        </div>
    </div>
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.text :options="['name' => 'title', 'label' => 'title', 'class' => 'col-md-12', 'isRequired' => true, 'isMultiLanguage' => true, 'value' => $post->title]" />
            <x-form.text-area :options="['name' => 'content', 'label' => 'content', 'class' => 'col-md-12', 'isRequired' => true, 'isMultiLanguage' => true, 'value' => $post->content]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush