@extends('admin.layouts.crud.table', [
    'rows' => $pages,
    'createRoute' => route('admin.pages.create'),
])

@section('table')
    @foreach ($pages as $page)
        <tr class="data-rows pages-table-row {{ $page->deleted_at ? 'deleted-table-row' : '' }}"
            data-page-id="{{ $page->id }}">
            @if (! $page->deleted_at)
                <td class="dt-checkboxes-cell">
                    <input type="checkbox" value="{{ $page->id }}" data-id="{{ $page->id }}"
                           class="dt-checkboxes form-check-input"
                           aria-label="{{ __('admin/main.select_row', ['name' => $page->slug]) }}">
                </td>
            @else
                <td></td>
            @endif

            <td>
                <div class="d-flex align-items-center gap-2">
                    @if ($page->icon)
                        <i class="{{ $page->icon }}"></i>
                    @endif
                    <span class="fw-semibold">{{ $page->title }}</span>
                </div>
            </td>

            <td class="text-nowrap">{{ $page->slug }}</td>

            <td>
                <span class="badge bg-label-{{ $page->type === 'public' ? 'success' : ($page->type === 'user' ? 'primary' : 'warning') }}">
                    {{ __('admin/main.' . $page->type) }}
                </span>
            </td>

            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap">
                    <a href="{{ route('admin.pages.show', ['page' => $page]) }}"
                       class="custom-icon admins-action-btn admins-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (! $page->deleted_at)
                        <a href="{{ route('admin.pages.edit', ['page' => $page]) }}"
                           class="custom-icon admins-action-btn admins-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($page->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $page->id }}"
                           data-route="{{ route('admin.pages.restore', ['id' => $page->id]) }}"
                           class="custom-icon admins-action-btn admins-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $page->id }}"
                           data-route="{{ route('admin.pages.destroy', ['page' => $page]) }}"
                           class="custom-icon admins-action-btn admins-action-delete delete-record"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.delete')"
                           aria-label="@lang('admin/main.delete')">
                            <i class="ti ti-trash" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
@endsection