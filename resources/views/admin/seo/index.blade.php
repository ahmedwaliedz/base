@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons createRoute="{{ route('admin.seo.create') }}" :hasDeleteAll="true" :deleteAllRoute="route('admin.seo.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'meta_title']]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.seo.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.image'), __('admin/main.meta_title'), __('admin/main.meta_description'), __('admin/main.type'), __('admin/main.actions')]" />
@endpush