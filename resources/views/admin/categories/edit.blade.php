@extends('admin.layouts.crud.edit')

@push('content')
<form id="editCategoryForm" class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.categories.update', ['category' => $category]) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="admins-form-section">
        <div class="row g-3">
            <x-form.text :options="['name' => 'slug', 'label' => __('admin/main.slug'), 'class' => 'col-md-12', 'isRequired' => true, 'value' => $category->slug]" />
            <x-form.text :options="['name' => 'icon', 'label' => __('admin/main.icon'), 'class' => 'col-md-12', 'value' => $category->icon]" />
            <x-form.select :options="['name' => 'parent_id', 'label' => __('admin/main.parent'), 'class' => 'col-md-12', 'value' => $category->parent_id, 'options' => App\Models\Category::whereNull('parent_id')->where('id', '!=', $category->id)->get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])]" />
            <x-form.select :options="['name' => 'is_active', 'label' => __('admin/main.is_active'), 'class' => 'col-md-12', 'isRequired' => true, 'value' => $category->is_active, 'options' => [['id' => 1, 'name' => __('admin/main.yes')], ['id' => 0, 'name' => __('admin/main.no')]]]" />
            <x-form.text :options="['name' => 'name', 'label' => __('admin/main.name'), 'class' => 'col-md-12', 'isRequired' => true, 'isMultiLanguage' => true, 'value' => $category->name]" />
        </div>
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3"><button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>{{ __('admin/main.update') }}</button></div>
</form>
@endpush