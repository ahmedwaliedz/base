@extends('admin.layouts.crud.table', ['rows' => $districts, 'createRoute' => route('admin.districts.create')])

@section('table')
    @foreach ($districts as $district)
        <tr class="data-rows {{ $district->deleted_at ? 'deleted-table-row' : '' }}" data-district-id="{{ $district->id }}">
            @if (!$district->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $district->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $district->name }}</td>
            <td>{{ $district->city?->name ?? '—' }}</td>
            <td>@if(!$district->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $district->id }}" data-route="{{ route('admin.districts.switchIsActive', ['id' => $district->id]) }}" {{ $district->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap districts-row-actions">
                    <a href="{{ route('admin.districts.show', ['district' => $district]) }}"
                       class="custom-icon districts-action-btn districts-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$district->deleted_at)
                        <a href="{{ route('admin.districts.edit', ['district' => $district]) }}"
                           class="custom-icon districts-action-btn districts-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($district->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $district->id }}"
                           data-route="{{ route('admin.districts.restore', ['id' => $district->id]) }}"
                           class="custom-icon districts-action-btn districts-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $district->id }}"
                           data-route="{{ route('admin.districts.destroy', ['district' => $district]) }}"
                           class="custom-icon districts-action-btn districts-action-delete delete-record"
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