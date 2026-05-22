@extends('admin.layouts.crud.show', ['model' => $page])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-file-text" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.page_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.pages.edit', ['page' => $page]) }}" class="btn btn-sm btn-success">
            <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
        </a>
        <a href="#" data-id="{{ $page->id }}"
           data-route="{{ route('admin.pages.destroy', ['page' => $page]) }}"
           class="btn btn-sm btn-danger delete-record">
            <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
        </a>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.page_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-link', 'label' => __('admin/main.slug'), 'value' => $page->slug])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-file-type', 'label' => __('admin/main.type'), 'value' => __('admin/main.' . $page->type->value)])
                    @if ($page->icon)
                        @include('admin.admins.parts.detail-row', ['icon' => 'ti-icons', 'label' => __('admin/main.icon'), 'value' => $page->icon])
                    @endif
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.title'), 'value' => $page->title])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-align-left', 'label' => __('admin/main.content'), 'value' => Str::limit(strip_tags($page->content), 200)])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $page->created_at?->format('Y-m-d H:i')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar-event', 'label' => __('admin/main.updated_at'), 'value' => $page->updated_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
