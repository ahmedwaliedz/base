@extends('admin.layouts.crud.table', ['rows' => $sliders, 'createRoute' => route('admin.sliders.create')])

@section('table')
    @foreach ($sliders as $slider)
        <tr class="data-rows {{ $slider->deleted_at ? 'deleted-table-row' : '' }}" data-slider-id="{{ $slider->id }}">
            @if (!$slider->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $slider->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $slider->image }}" class="rounded-2" alt=""></div></td>
            <td>{{ $slider->title }}</td>
            <td class="text-nowrap">{{ $slider->link }}</td>
            <td>@if(!$slider->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $slider->id }}" data-route="{{ route('admin.sliders.switchActive', ['id' => $slider->id]) }}" {{ $slider->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.sliders.show', ['slider' => $slider]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$slider->deleted_at)<a href="{{ route('admin.sliders.edit', ['slider' => $slider]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($slider->deleted_at)<a href="javascript:void(0);" data-id="{{ $slider->id }}" data-route="{{ route('admin.sliders.restore', ['id' => $slider->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $slider->id }}" data-route="{{ route('admin.sliders.destroy', ['slider' => $slider]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection