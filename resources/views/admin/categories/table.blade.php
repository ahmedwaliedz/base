@extends('admin.layouts.crud.table', ['rows' => $categories, 'createRoute' => route('admin.categories.create')])

@section('table')
    @foreach ($categories as $category)
        <tr class="data-rows {{ method_exists($category, 'trashed') && $category->trashed() ? 'deleted-table-row' : '' }}" data-category-id="{{ $category->id }}">
            @if (!(method_exists($category, 'trashed') && $category->trashed()))<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $category->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="d-flex align-items-center gap-2">@if($category->icon)<i class="{{ $category->icon }}"></i>@endif<span>{{ $category->name }}</span></div></td>
            <td>{{ $category->parent?->name ?? '—' }}</td>
            <td><span class="badge bg-label-info">{{ $category->children_count ?? 0 }}</span></td>
            <td>@if(!(method_exists($category, 'trashed') && $category->trashed()))<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $category->id }}" data-route="{{ route('admin.categories.switchIsActive', ['id' => $category->id]) }}" {{ $category->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap categories-row-actions">
                    <a href="{{ route('admin.categories.show', ['category' => $category]) }}"
                       class="custom-icon categories-action-btn categories-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!(method_exists($category, 'trashed') && $category->trashed()))
                        <a href="{{ route('admin.categories.edit', ['category' => $category]) }}"
                           class="custom-icon categories-action-btn categories-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (method_exists($category, 'trashed') && $category->trashed())
                        <a href="javascript:void(0);" data-id="{{ $category->id }}"
                           data-route="{{ route('admin.categories.restore', ['id' => $category->id]) }}"
                           class="custom-icon categories-action-btn categories-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $category->id }}"
                           data-route="{{ route('admin.categories.destroy', ['category' => $category]) }}"
                           class="custom-icon categories-action-btn categories-action-delete delete-record"
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