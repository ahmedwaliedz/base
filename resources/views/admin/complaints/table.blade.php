@extends('admin.layouts.crud.table', ['rows' => $complaints, 'createRoute' => null])

@section('table')
    @foreach ($complaints as $complaint)
        <tr class="data-rows {{ $complaint->deleted_at ? 'deleted-table-row' : '' }}" data-complaint-id="{{ $complaint->id }}">
            @if (!$complaint->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $complaint->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $complaint->name }}</td>
            <td>{{ Str::limit($complaint->subject, 30) }}</td>
            <td><span class="badge bg-label-{{ $complaint->type === 'suggestion' ? 'info' : 'warning' }}">{{ __('admin/main.' . $complaint->type) }}</span></td>
            <td><span class="badge bg-label-{{ $complaint->status === 'solved' ? 'success' : 'danger' }}">{{ __('admin/main.' . $complaint->status) }}</span></td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.complaints.show', ['complaint' => $complaint]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if($complaint->deleted_at)<a href="javascript:void(0);" data-id="{{ $complaint->id }}" data-route="{{ route('admin.complaints.restore', ['id' => $complaint->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $complaint->id }}" data-route="{{ route('admin.complaints.destroy', ['complaint' => $complaint]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection