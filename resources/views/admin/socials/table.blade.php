@extends('admin.layouts.crud.table', ['rows' => $socials, 'createRoute' => route('admin.socials.create')])

@section('table')
    @foreach ($socials as $social)
        <tr class="data-rows {{ $social->deleted_at ? 'deleted-table-row' : '' }}" data-social-id="{{ $social->id }}">
            @if (!$social->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $social->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $social->image }}" class="rounded-2" alt=""></div></td>
            <td class="text-nowrap">{{ $social->link }}</td>
            <td>@if(!$social->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $social->id }}" data-route="{{ route('admin.socials.switchIsActive', ['id' => $social->id]) }}" {{ $social->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.socials.show', ['social' => $social]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$social->deleted_at)<a href="{{ route('admin.socials.edit', ['social' => $social]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($social->deleted_at)<a href="javascript:void(0);" data-id="{{ $social->id }}" data-route="{{ route('admin.socials.restore', ['id' => $social->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $social->id }}" data-route="{{ route('admin.socials.destroy', ['social' => $social]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection