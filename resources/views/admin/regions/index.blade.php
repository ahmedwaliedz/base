@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons createRoute="{{ route('admin.regions.create') }}" :hasDeleteAll="true" :deleteAllRoute="route('admin.regions.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'name'], ['type' => 'select', 'name' => 'is_active', 'options' => [['', __('admin/main.all')], ['active_only', __('admin/main.active')], ['inactive_only', __('admin/main.inactive')]]]]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.regions.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.name'), __('admin/main.country'), __('admin/main.cities'), __('admin/main.status'), __('admin/main.actions')]" />
@endpush