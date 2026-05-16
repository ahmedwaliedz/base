@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons
        createRoute="{{ route('admin.sliders.create') }}"
        :hasDeleteAll="true"
        :deleteAllRoute="route('admin.sliders.destroyAll')"
        :hasReload="true"
        :hasFilter="true"
        :hasSearch="true"
        :hasExport="true"
        :hasPagination="true"
    />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'title']]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.sliders.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.image'), __('admin/main.title'), __('admin/main.link'), __('admin/main.status'), __('admin/main.actions')]" />
@endpush