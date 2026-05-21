# Show View Template

## Purpose

Use when creating the `show.blade.php` view for an admin CRUD module.

---

## Structure Overview

```
Header - icon + title + action buttons
Stat Cards Row - 4 cards (primary, success, info, warning)
Profile Card (left) + Details Card (right) - side by side
Related Data Section - optional, below the cards
```

---

## Full Template

```blade
@extends('admin.layouts.crud.show')

@push('content')
    {{-- Header --}}
    <div class="admins-show-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="admins-show-header-icon">
                <i class="ti ti-{icon}"></i>
            </div>
            <h4 class="mb-0">{{ ${model_var}->name ?? __('admin/main.{section_name}') }}</h4>
        </div>
        <div class="admins-show-header-actions">
            <a href="{{ route('admin.{section}.edit', ${model_var}) }}" class="btn btn-success btn-sm" title="{{ __('admin/main.edit') }}">
                <i class="ti ti-pencil"></i> {{ __('admin/main.edit') }}
            </a>
            <a href="{{ route('admin.{section}.index') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left"></i> {{ __('admin/main.back') }}
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <x-table.statistics class="mb-4">
        <x-slot:cards>
            <div class="card admin-stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ __('admin/main.{stat1_label}') }}</h6>
                            <h3 class="mb-0">{{ ${stat1_value} }}</h3>
                        </div>
                        <i class="ti ti-{stat1_icon} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card admin-stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ __('admin/main.{stat2_label}') }}</h6>
                            <h3 class="mb-0">{{ ${stat2_value} }}</h3>
                        </div>
                        <i class="ti ti-{stat2_icon} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card admin-stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ __('admin/main.{stat3_label}') }}</h6>
                            <h3 class="mb-0">{{ ${stat3_value} }}</h3>
                        </div>
                        <i class="ti ti-{stat3_icon} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card admin-stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ __('admin/main.{stat4_label}') }}</h6>
                            <h3 class="mb-0">{{ ${stat4_value} }}</h3>
                        </div>
                        <i class="ti ti-{stat4_icon} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </x-slot:cards>
    </x-table.statistics>

    {{-- Profile + Details Row --}}
    <div class="row mb-4">
        {{-- Profile Card --}}
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card admin-profile-card h-100">
                <div class="card-body text-center">
                    <div class="admin-avatar mb-3">
                        @if(${model_var}->getFirstMediaUrl('{collection}'))
                            <img src="{{ ${model_var}->getFirstMediaUrl('{collection}') }}" alt="{{ ${model_var}->name }}" class="rounded-circle" width="120">
                        @else
                            <div class="admin-avatar-placeholder rounded-circle d-inline-flex align-items-center justify-content-center" style="width:120px;height:120px;">
                                <i class="ti ti-user fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ ${model_var}->name }}</h5>
                    <p class="text-muted mb-0">{{ ${model_var}->email ?? '' }}</p>
                </div>
            </div>
        </div>

        {{-- Details Card --}}
        <div class="col-md-8">
            <div class="card admin-details-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('admin/main.details') }}</h5>
                </div>
                <div class="card-body">
                    @include('admin.{section}.parts.detail-row', ['label' => __('admin/main.{field1}'), 'value' => ${model_var}->{field1}])
                    @include('admin.{section}.parts.detail-row', ['label' => __('admin/main.{field2}'), 'value' => ${model_var}->{field2}])
                    @include('admin.{section}.parts.detail-row', ['label' => __('admin/main.{field3}'), 'value' => ${model_var}->{field3}])
                </div>
            </div>
        </div>
    </div>

    {{-- Related Data Section (optional) --}}
    @if(${related_data}->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('admin/main.{related_section_name}') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('admin/main.{col1}') }}</th>
                            <th>{{ __('admin/main.{col2}') }}</th>
                            <th>{{ __('admin/main.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(${related_data} as ${item})
                        <tr>
                            <td>{{ ${item}->{field1} }}</td>
                            <td>{{ ${item}->{field2} }}</td>
                            <td>
                                <a href="{{ route('admin.{related_section}.show', ${item}) }}" class="custom-icon {related_section}-action-btn {related_section}-action-view" data-bs-toggle="tooltip" title="{{ __('admin/main.show') }}" aria-label="{{ __('admin/main.show') }}">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@endpush
```

---

## Required Data from Controller

The controller must pass these variables:
- `${model_var}` - the model instance
- `${stat1_value}`, `${stat2_value}`, `${stat3_value}`, `${stat4_value}` - stat card values
- `${related_data}` - optional collection for related records

When using `CrudBaseService::show()`, the `'model' => $item` key is automatically provided.

---

## Partial Reference

Use `resources/views/admin/admins/parts/detail-row.blade.php` for field rows:
```blade
<div class="row mb-2">
    <div class="col-sm-4 fw-bold text-muted">{{ $label }}</div>
    <div class="col-sm-8">{{ $value ?? '&mdash;' }}</div>
</div>
```

Use `resources/views/admin/admins/parts/file-detail-row.blade.php` for file/URL fields.
