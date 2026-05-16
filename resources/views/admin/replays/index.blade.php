@extends('admin.layouts.crud.index')

@push('content')
    <x-table.buttons :hasDeleteAll="true" :deleteAllRoute="route('admin.replays.destroyAll')" :hasReload="true" :hasFilter="true" :hasSearch="true" :hasPagination="true" />
    <x-table.filter :mainCol="'col-md-3'" :filters="[]" />
    <x-table.bulk-actions :hasDelete="true" :deleteRoute="route('admin.replays.destroyAll')" />
    <x-table.table :hasCheckbox="true" :hasActions="true" :headers="[__('admin/main.replay'), __('admin/main.type'), __('admin/main.actions')]" />
@endpush