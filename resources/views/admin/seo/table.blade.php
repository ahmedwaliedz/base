@extends('admin.layouts.crud.table', ['rows' => $seos, 'createRoute' => route('admin.seo.create')])

@section('table')
    @foreach ($seos as $seo)
        <tr class="data-rows {{ $seo->deleted_at ? 'deleted-table-row' : '' }}" data-seo-id="{{ $seo->id }}">
            @if (!$seo->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $seo->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ Str::limit($seo->meta_title, 40) }}</td>
            <td>{{ Str::limit($seo->meta_description, 60) }}</td>
            <td><span class="badge bg-label-info">{{ class_basename($seo->seoable_type) }}</span></td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.seo.show', ['seo' => $seo]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$seo->deleted_at)<a href="{{ route('admin.seo.edit', ['seo' => $seo]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($seo->deleted_at)<a href="javascript:void(0);" data-id="{{ $seo->id }}" data-route="{{ route('admin.seo.restore', ['id' => $seo->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $seo->id }}" data-route="{{ route('admin.seo.destroy', ['seo' => $seo]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection