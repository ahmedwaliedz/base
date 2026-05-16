@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons :hasDeleteAll="true" :deleteAllRoute="route('admin.contact-messages.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'name'], ['type' => 'select', 'name' => 'is_read', 'options' => [['', __('admin/main.all')], ['read', __('admin/main.read')], ['unread', __('admin/main.unread')]]]]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.contact-messages.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.name'), __('admin/main.email'), __('admin/main.subject'), __('admin/main.read'), __('admin/main.actions')]" />
@endpush