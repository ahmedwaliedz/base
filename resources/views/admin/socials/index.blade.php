@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons createRoute="{{ route('admin.socials.create') }}" :hasDeleteAll="true" :deleteAllRoute="route('admin.socials.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.socials.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.image'), __('admin/main.link'), __('admin/main.status'), __('admin/main.actions')]" />
@endpush