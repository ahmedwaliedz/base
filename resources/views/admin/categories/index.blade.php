@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons createRoute="{{ route('admin.categories.create') }}" :hasDeleteAll="true" :deleteAllRoute="route('admin.categories.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasExport="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'name'], ['type' => 'select', 'name' => 'is_active', 'options' => [['id' => '', 'name' => __('admin/main.all')], ['id' => 'active_only', 'name' => __('admin/main.active')], ['id' => 'inactive_only', 'name' => __('admin/main.inactive')]]]]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.categories.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.name'), __('admin/main.parent'), __('admin/main.subcategories'), __('admin/main.status'), __('admin/main.actions')]" />
@endpush