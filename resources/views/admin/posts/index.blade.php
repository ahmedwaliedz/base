@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons createRoute="{{ route('admin.posts.create') }}" :hasDeleteAll="true" :deleteAllRoute="route('admin.posts.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'title'], ['type' => 'select', 'name' => 'is_active', 'options' => [['id' => '', 'name' => __('admin/main.all')], ['id' => 'active_only', 'name' => __('admin/main.active')], ['id' => 'inactive_only', 'name' => __('admin/main.inactive')]]]]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.posts.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.image'), __('admin/main.title'), __('admin/main.status'), __('admin/main.actions')]" />
@endpush