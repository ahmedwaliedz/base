@extends('admin.layouts.crud.table', ['rows' => $introPages, 'createRoute' => route('admin.intro-pages.create')])

@section('table')
    @foreach ($introPages as $introPage)
        <tr class="data-rows {{ $introPage->deleted_at ? 'deleted-table-row' : '' }}" data-intro-page-id="{{ $introPage->id }}">
            @if (!$introPage->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $introPage->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $introPage->image }}" class="rounded-2" alt=""></div></td>
            <td>{{ $introPage->title }}</td>
            <td class="text-nowrap">{{ $introPage->link }}</td>
            <td>@if(!$introPage->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $introPage->id }}" data-route="{{ route('admin.intro-pages.switchIsActive', ['id' => $introPage->id]) }}" {{ $introPage->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.intro-pages.show', ['intro_page' => $introPage]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$introPage->deleted_at)<a href="{{ route('admin.intro-pages.edit', ['intro_page' => $introPage]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($introPage->deleted_at)<a href="javascript:void(0);" data-id="{{ $introPage->id }}" data-route="{{ route('admin.intro-pages.restore', ['id' => $introPage->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $introPage->id }}" data-route="{{ route('admin.intro-pages.destroy', ['intro_page' => $introPage]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection