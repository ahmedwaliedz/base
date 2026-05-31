@extends('admin.layouts.crud.show', ['model' => $post])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-news" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.post_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($post->deleted_at)
            <a href="#" data-id="{{ $post->id }}"
               data-route="{{ route('admin.posts.restore', ['id' => $post->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.posts.edit', ['post' => $post]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $post->id }}"
               data-route="{{ route('admin.posts.destroy', ['post' => $post]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-secondary">
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
                    {{ __('admin/main.post_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.file-detail-row', ['icon' => 'ti-photo', 'label' => __('admin/main.image'), 'value' => $post->image])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-article', 'label' => __('admin/main.title'), 'value' => $post->title])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-align-left', 'label' => __('admin/main.content'), 'value' => Str::limit(strip_tags($post->content), 300)])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-circle-check', 'label' => __('admin/main.status'), 'value' => $post->is_active ? __('admin/main.active') : __('admin/main.inactive')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $post->created_at?->format('Y-m-d H:i')])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar-event', 'label' => __('admin/main.updated_at'), 'value' => $post->updated_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>
@endpush
