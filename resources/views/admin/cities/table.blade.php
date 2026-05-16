@extends('admin.layouts.crud.table', ['rows' => $cities, 'createRoute' => route('admin.cities.create')])

@section('table')
    @foreach ($cities as $city)
        <tr class="data-rows {{ $city->deleted_at ? 'deleted-table-row' : '' }}" data-city-id="{{ $city->id }}">
            @if (!$city->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $city->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $city->name }}</td>
            <td>{{ $city->region?->name ?? '—' }}</td>
            <td><span class="badge bg-label-info">{{ $city->districts_count ?? 0 }}</span></td>
            <td>@if(!$city->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $city->id }}" data-route="{{ route('admin.cities.switchIsActive', ['id' => $city->id]) }}" {{ $city->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.cities.show', ['city' => $city]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$city->deleted_at)<a href="{{ route('admin.cities.edit', ['city' => $city]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($city->deleted_at)<a href="javascript:void(0);" data-id="{{ $city->id }}" data-route="{{ route('admin.cities.restore', ['id' => $city->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $city->id }}" data-route="{{ route('admin.cities.destroy', ['city' => $city]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection