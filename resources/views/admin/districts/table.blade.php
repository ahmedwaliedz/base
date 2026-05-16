@extends('admin.layouts.crud.table', ['rows' => $districts, 'createRoute' => route('admin.districts.create')])

@section('table')
    @foreach ($districts as $district)
        <tr class="data-rows {{ $district->deleted_at ? 'deleted-table-row' : '' }}" data-district-id="{{ $district->id }}">
            @if (!$district->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $district->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $district->name }}</td>
            <td>{{ $district->city?->name ?? '—' }}</td>
            <td>@if(!$district->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $district->id }}" data-route="{{ route('admin.districts.switchIsActive', ['id' => $district->id]) }}" {{ $district->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.districts.show', ['district' => $district]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$district->deleted_at)<a href="{{ route('admin.districts.edit', ['district' => $district]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($district->deleted_at)<a href="javascript:void(0);" data-id="{{ $district->id }}" data-route="{{ route('admin.districts.restore', ['id' => $district->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $district->id }}" data-route="{{ route('admin.districts.destroy', ['district' => $district]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection