# Table View Template

## Purpose

Use when creating the index/table view for an admin CRUD module.

---

## Structure Overview

```
Header -- title + create button
Statistics Cards Row -- optional
Filter Section -- optional
Table -- with colored action buttons, status toggle, pagination
```

---

## Full Template

```blade
@extends('admin.layouts.crud.index')

@push('content')
    {{-- Optional Statistics Cards --}}
    {{-- <x-table.statistics>
        <x-slot:cards>
            <div class="card admin-stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ __('admin/main.{stat_label}') }}</h6>
                            <h3 class="mb-0">{{ ${stat_value} }}</h3>
                        </div>
                        <i class="ti ti-{stat_icon} fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </x-slot:cards>
    </x-table.statistics> --}}

    {{-- Filter Section --}}
    {{-- <x-table.filter>
        <x-slot:filters>
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('admin/main.search') }}" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="is_active" class="form-control">
                        <option value="">{{ __('admin/main.all') }}</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('admin/main.active') }}</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('admin/main.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">{{ __('admin/main.filter') }}</button>
                    <a href="{{ route('admin.{section}.index') }}" class="btn btn-secondary">{{ __('admin/main.reset') }}</a>
                </div>
            </div>
        </x-slot:filters>
    </x-table.filter> --}}

    {{-- Main Table --}}
    <x-table.table>
        <x-slot:header>
            <tr>
                <th>#</th>
                <th>{{ __('admin/main.name') }}</th>
                <th>{{ __('admin/main.{field1}') }}</th>
                <th>{{ __('admin/main.{field2}') }}</th>
                <th>{{ __('admin/main.status') }}</th>
                <th>{{ __('admin/main.actions') }}</th>
            </tr>
        </x-slot:header>
        <x-slot:body>
            @forelse($items as ${item})
            <tr @if(${item}->deleted_at) class="table-danger" @endif>
                <td>{{ ${item}->id }}</td>
                <td>{{ ${item}->name }}</td>
                <td>{{ ${item}->{field1} }}</td>
                <td>{{ ${item}->{field2} }}</td>
                <td>
                    @include('admin.{section}.parts.switch-is-active', ['item' => ${item}])
                </td>
                <td class="{section}-actions-cell">
                    <div class="d-flex align-items-center gap-2 flex-nowrap {section}-row-actions">
                        @can('{section}.show')
                        <a href="{{ route('admin.{section}.show', ${item}) }}"
                           class="custom-icon {section}-action-btn {section}-action-view"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.show')"
                           aria-label="@lang('admin/main.show')">
                            <i class="ti ti-eye" aria-hidden="true"></i>
                        </a>
                        @endcan

                        @if (! ${item}->deleted_at)
                            @can('{section}.edit')
                            <a href="{{ route('admin.{section}.edit', ${item}) }}"
                                class="custom-icon {section}-action-btn {section}-action-edit"
                               data-bs-toggle="tooltip" data-bs-placement="top"
                               title="@lang('admin/main.edit')"
                               aria-label="@lang('admin/main.edit')">
                                <i class="ti ti-pencil" aria-hidden="true"></i>
                            </a>
                            @endcan
                        @endif

                        @if (${item}->deleted_at)
                            @can('{section}.restore')
                            <a href="javascript:void(0);" data-id="{{ ${item}->id }}"
                               data-route="{{ route('admin.{section}.restore', ['id' => ${item}->id]) }}"
                                class="custom-icon {section}-action-btn {section}-action-restore restore-row"
                               data-bs-toggle="tooltip" data-bs-placement="top"
                               title="@lang('admin/main.restore')"
                               aria-label="@lang('admin/main.restore')">
                                <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                            </a>
                            @endcan
                        @else
                            @can('{section}.destroy')
                            <a href="javascript:void(0);" data-id="{{ ${item}->id }}"
                               data-route="{{ route('admin.{section}.destroy', ${item}) }}"
                                class="custom-icon {section}-action-btn {section}-action-delete delete-record"
                               data-bs-toggle="tooltip" data-bs-placement="top"
                               title="@lang('admin/main.delete')"
                               aria-label="@lang('admin/main.delete')">
                                <i class="ti ti-trash" aria-hidden="true"></i>
                            </a>
                            @endcan
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">{{ __('admin/main.no_data') }}</td>
            </tr>
            @endforelse
        </x-slot:body>
    </x-table.table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $items->links() }}
    </div>
@endpush
```

---

## Action Button CSS Classes

| Button | Classes | Visual |
|--------|---------|--------|
| Show | `custom-icon {section}-action-btn {section}-action-view` | Blue icon button |
| Edit | `custom-icon {section}-action-btn {section}-action-edit` | Green icon button |
| Delete | `custom-icon {section}-action-btn {section}-action-delete` | Red icon button |
| Restore | `custom-icon {section}-action-btn {section}-action-restore` | Teal icon button |

All action buttons use `{section}-row-actions` as the wrapper container and `{section}-actions-cell` on the `<td>`. Generic CSS selectors in `filter.css` use `[class*="-action-btn"]`, `[class*="-action-view"]`, etc. to style all sections.

---

## Deleted Row Handling

- Check `$item->deleted_at` before rendering delete vs restore buttons
- Disable bulk-action checkboxes for soft-deleted rows
- When `$item->trashed()`, show restore button instead of delete
- Table row gets `class="table-danger"` for deleted items

---

## Required Data from Controller

The controller must pass:
- `$items` -- paginated collection of models
- `$stat_*` (optional) -- statistics card values
