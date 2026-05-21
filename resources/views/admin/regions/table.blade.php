@extends('admin.layouts.crud.table', ['rows' => $regions, 'createRoute' => route('admin.regions.create')])

@section('table')
    @foreach ($regions as $region)
        <tr class="data-rows {{ $region->deleted_at ? 'deleted-table-row' : '' }}" data-region-id="{{ $region->id }}">
            @if (!$region->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $region->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $region->name }}</td>
            <td>{{ $region->country?->name ?? '—' }}</td>
            <td><span class="badge bg-label-info">{{ $region->cities_count ?? 0 }}</span></td>
            <td>@if(!$region->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $region->id }}" data-route="{{ route('admin.regions.switchIsActive', ['id' => $region->id]) }}" {{ $region->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap regions-row-actions">
                    <a href="{{ route('admin.regions.show', ['region' => $region]) }}"
                       class="custom-icon regions-action-btn regions-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$region->deleted_at)
                        <a href="{{ route('admin.regions.edit', ['region' => $region]) }}"
                           class="custom-icon regions-action-btn regions-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($region->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $region->id }}"
                           data-route="{{ route('admin.regions.restore', ['id' => $region->id]) }}"
                           class="custom-icon regions-action-btn regions-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $region->id }}"
                           data-route="{{ route('admin.regions.destroy', ['region' => $region]) }}"
                           class="custom-icon regions-action-btn regions-action-delete delete-record"
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