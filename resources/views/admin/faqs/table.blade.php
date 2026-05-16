@extends('admin.layouts.crud.table', ['rows' => $faqs, 'createRoute' => route('admin.faqs.create')])

@section('table')
    @foreach ($faqs as $faq)
        <tr class="data-rows {{ $faq->deleted_at ? 'deleted-table-row' : '' }}" data-faq-id="{{ $faq->id }}">
            @if (!$faq->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $faq->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ Str::limit($faq->question, 50) }}</td>
            <td>{{ Str::limit($faq->answer, 80) }}</td>
            <td>@if(!$faq->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $faq->id }}" data-route="{{ route('admin.faqs.switchIsActive', ['id' => $faq->id]) }}" {{ $faq->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.faqs.show', ['faq' => $faq]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$faq->deleted_at)<a href="{{ route('admin.faqs.edit', ['faq' => $faq]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($faq->deleted_at)<a href="javascript:void(0);" data-id="{{ $faq->id }}" data-route="{{ route('admin.faqs.restore', ['id' => $faq->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $faq->id }}" data-route="{{ route('admin.faqs.destroy', ['faq' => $faq]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection