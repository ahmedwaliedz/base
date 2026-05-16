@extends('admin.layouts.crud.table', ['rows' => $regions, 'createRoute' => route('admin.regions.create')])

@section('table')
    @foreach ($regions as $region)
        <tr class="data-rows {{ $region->deleted_at ? 'deleted-table-row' : '' }}" data-region-id="{{ $region->id }}">
            @if (!$region->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $region->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $region->name }}</td>
            <td>{{ $region->country?->name ?? '—' }}</td>
            <td><span class="badge bg-label-info">{{ $region->cities_count ?? 0 }}</span></td>
            <td>@if(!$region->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $region->id }}" data-route="{{ route('admin.regions.switchIsActive', ['id' => $region->id]) }}" {{ $region->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.regions.show', ['region' => $region]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$region->deleted_at)<a href="{{ route('admin.regions.edit', ['region' => $region]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($region->deleted_at)<a href="javascript:void(0);" data-id="{{ $region->id }}" data-route="{{ route('admin.regions.restore', ['id' => $region->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $region->id }}" data-route="{{ route('admin.regions.destroy', ['region' => $region]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection