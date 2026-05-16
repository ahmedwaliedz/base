@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons createRoute="{{ route('admin.faqs.create') }}" :hasDeleteAll="true" :deleteAllRoute="route('admin.faqs.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasExport="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[['type' => 'text', 'name' => 'question'], ['type' => 'select', 'name' => 'is_active', 'options' => [['id' => '', 'name' => __('admin/main.all')], ['id' => 'active_only', 'name' => __('admin/main.active')], ['id' => 'inactive_only', 'name' => __('admin/main.inactive')]]]]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.faqs.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.question'), __('admin/main.answer'), __('admin/main.status'), __('admin/main.actions')]" />
@endpush