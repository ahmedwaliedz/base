@extends('admin.layouts.crud.table', ['rows' => $categories, 'createRoute' => route('admin.categories.create')])

@section('table')
    @foreach ($categories as $category)
        <tr class="data-rows {{ $category->deleted_at ? 'deleted-table-row' : '' }}" data-category-id="{{ $category->id }}">
            @if (!$category->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $category->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="d-flex align-items-center gap-2">@if($category->icon)<i class="{{ $category->icon }}"></i>@endif<span>{{ $category->name }}</span></div></td>
            <td>{{ $category->parent?->name ?? '—' }}</td>
            <td><span class="badge bg-label-info">{{ $category->children_count ?? 0 }}</span></td>
            <td>@if(!$category->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $category->id }}" data-route="{{ route('admin.categories.switchIsActive', ['id' => $category->id]) }}" {{ $category->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.categories.show', ['category' => $category]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$category->deleted_at)<a href="{{ route('admin.categories.edit', ['category' => $category]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($category->deleted_at)<a href="javascript:void(0);" data-id="{{ $category->id }}" data-route="{{ route('admin.categories.restore', ['id' => $category->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $category->id }}" data-route="{{ route('admin.categories.destroy', ['category' => $category]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection