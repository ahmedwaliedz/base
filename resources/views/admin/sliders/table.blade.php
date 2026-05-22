@extends('admin.layouts.crud.table', ['rows' => $sliders, 'createRoute' => route('admin.sliders.create')])

@section('table')
    @foreach ($sliders as $slider)
        <tr class="data-rows {{ $slider->deleted_at ? 'deleted-table-row' : '' }}" data-slider-id="{{ $slider->id }}">
            @if (!$slider->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $slider->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $slider->image ?: asset('style/admin/img/placeholder.png') }}" class="rounded-2" alt="" style="width:36px;height:36px;object-fit:cover"></div></td>
            <td>{{ $slider->title }}</td>
            <td class="text-nowrap">{{ $slider->link }}</td>
            <td>@if(!$slider->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $slider->id }}" data-route="{{ route('admin.sliders.switchActive', ['id' => $slider->id]) }}" {{ $slider->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap sliders-row-actions">
                    <a href="{{ route('admin.sliders.show', ['slider' => $slider]) }}"
                       class="custom-icon sliders-action-btn sliders-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$slider->deleted_at)
                        <a href="{{ route('admin.sliders.edit', ['slider' => $slider]) }}"
                           class="custom-icon sliders-action-btn sliders-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($slider->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $slider->id }}"
                           data-route="{{ route('admin.sliders.restore', ['id' => $slider->id]) }}"
                           class="custom-icon sliders-action-btn sliders-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $slider->id }}"
                           data-route="{{ route('admin.sliders.destroy', ['slider' => $slider]) }}"
                           class="custom-icon sliders-action-btn sliders-action-delete delete-record"
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