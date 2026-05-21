@extends('admin.layouts.crud.show', ['model' => $category])

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/admins.css') }}">
@endpush

@push('header')
    <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap">
        <i class="ti ti-category" style="color: var(--color-brand-primary);"></i>
        {{ __('admin/main.category_details') }}
    </h5>
    <div class="d-flex gap-2 flex-wrap">
        @if ($category->deleted_at)
            <a href="#" data-id="{{ $category->id }}"
               data-route="{{ route('admin.categories.restore', ['id' => $category->id]) }}"
               class="btn btn-sm btn-success restore-row">
                <i class="ti ti-arrow-back-up me-1"></i>{{ __('admin/main.restore') }}
            </a>
        @else
            <a href="{{ route('admin.categories.edit', ['category' => $category]) }}" class="btn btn-sm btn-success">
                <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
            </a>
            <a href="#" data-id="{{ $category->id }}"
               data-route="{{ route('admin.categories.destroy', ['category' => $category]) }}"
               class="btn btn-sm btn-danger delete-record">
                <i class="ti ti-trash me-1"></i>{{ __('admin/main.delete') }}
            </a>
        @endif
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
        </a>
    </div>
@endpush

@push('content')
    <div class="col-12 mb-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--{{ $category->is_active ? 'info' : 'warning' }}">
                    <div class="admin-stat-card__icon">
                        <i class="ti {{ $category->is_active ? 'ti-circle-check' : 'ti-circle-off' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.status') }}</div>
                        <div class="admin-stat-card__value">
                            {{ $category->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--primary">
                    <div class="admin-stat-card__icon"><i class="ti ti-hierarchy-2"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.subcategories') }}</div>
                        <div class="admin-stat-card__value">{{ number_format($category->children->count()) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--success">
                    <div class="admin-stat-card__icon"><i class="ti ti-link"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.slug') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $category->slug }}">
                            {{ $category->slug }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="admin-stat-card admin-stat-card--secondary">
                    <div class="admin-stat-card__icon"><i class="ti ti-user"></i></div>
                    <div class="min-w-0">
                        <div class="admin-stat-card__label">{{ __('admin/main.parent') }}</div>
                        <div class="admin-stat-card__value text-truncate" title="{{ $category->parent?->name }}">
                            {{ $category->parent?->name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-5 mb-4">
        <div class="admin-profile-card">
            <div class="admin-profile-card__avatar-frame">
                @if ($category->icon)
                    <div class="d-flex align-items-center justify-content-center h-100" style="font-size: 2rem;">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                @else
                    <i class="ti ti-category" style="font-size: 2rem; color: var(--color-brand-primary);"></i>
                @endif
            </div>
            <h5 class="admin-profile-card__name">{{ $category->name }}</h5>
            <div class="text-muted small">{{ $category->slug }}</div>
        </div>
    </div>

    <div class="col-xl-8 col-md-7 mb-4">
        <div class="admin-details-card">
            <div class="admin-details-card__head">
                <h6 class="mb-0">
                    <i class="ti ti-info-circle me-1" style="color: var(--color-brand-primary);"></i>
                    {{ __('admin/main.category_details') }}
                </h6>
            </div>
            <div class="admin-details-card__body">
                <div class="row g-3">
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-tag', 'label' => __('admin/main.name'), 'value' => $category->name])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-link', 'label' => __('admin/main.slug'), 'value' => $category->slug])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-hierarchy-2', 'label' => __('admin/main.parent'), 'value' => $category->parent?->name])
                    @if ($category->icon)
                        @include('admin.admins.parts.detail-row', ['icon' => 'ti-icons', 'label' => __('admin/main.icon'), 'value' => $category->icon])
                    @endif
                    @include('admin.admins.parts.file-detail-row', ['icon' => 'ti-photo', 'label' => __('admin/main.image'), 'value' => $category->image])
                    @include('admin.admins.parts.detail-row', ['icon' => 'ti-calendar', 'label' => __('admin/main.created_at'), 'value' => $category->created_at?->format('Y-m-d H:i')])
                </div>
            </div>
        </div>
    </div>

    @if (($category->children ?? collect())->isNotEmpty())
        <div class="col-12 mb-4">
            <div class="admin-details-card">
                <div class="admin-details-card__head d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="mb-0">
                        <i class="ti ti-hierarchy-2 me-1" style="color: var(--color-brand-primary);"></i>
                        {{ __('admin/main.related_subcategories') }}
                    </h6>
                    <span class="badge bg-label-primary">{{ number_format($category->children->count()) }}</span>
                </div>
                <div class="admin-details-card__body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('admin/main.id') }}</th>
                                    <th scope="col">{{ __('admin/main.name') }}</th>
                                    <th scope="col">{{ __('admin/main.slug') }}</th>
                                    <th scope="col" class="text-center">{{ __('admin/main.is_active') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category->children as $child)
                                    <tr>
                                        <td class="text-nowrap text-muted small">{{ $child->id }}</td>
                                        <td>{{ $child->name }}</td>
                                        <td>{{ $child->slug }}</td>
                                        <td class="text-center">
                                            @if ($child->is_active)
                                                <span class="badge bg-label-success">{{ __('admin/main.active') }}</span>
                                            @else
                                                <span class="badge bg-label-secondary">{{ __('admin/main.inactive') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpush
