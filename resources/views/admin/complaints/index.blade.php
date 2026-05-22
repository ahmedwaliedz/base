@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons :hasDeleteAll="true" :deleteAllRoute="route('admin.complaints.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'name'], ['type' => 'text', 'name' => 'subject']]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.complaints.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.name'), __('admin/main.subject'), __('admin/main.type'), __('admin/main.status'), __('admin/main.actions')]" />
@endpush