@extends('admin.layouts.crud.table', ['rows' => $complaints, 'createRoute' => null])

@section('table')
    @foreach ($complaints as $complaint)
        <tr class="data-rows {{ $complaint->deleted_at ? 'deleted-table-row' : '' }}" data-complaint-id="{{ $complaint->id }}">
            @if (!$complaint->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $complaint->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $complaint->name }}</td>
            <td>{{ Str::limit($complaint->subject, 30) }}</td>
            <td><span class="badge bg-label-{{ $complaint->type === 'suggestion' ? 'info' : 'warning' }}">{{ __('admin/main.' . $complaint->type) }}</span></td>
            <td><span class="badge bg-label-{{ $complaint->status === 'solved' ? 'success' : 'danger' }}">{{ __('admin/main.' . $complaint->status) }}</span></td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap complaints-row-actions">
                    <a href="{{ route('admin.complaints.show', ['complaint' => $complaint]) }}"
                       class="custom-icon complaints-action-btn complaints-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if ($complaint->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $complaint->id }}"
                           data-route="{{ route('admin.complaints.restore', ['id' => $complaint->id]) }}"
                           class="custom-icon complaints-action-btn complaints-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $complaint->id }}"
                           data-route="{{ route('admin.complaints.destroy', ['complaint' => $complaint]) }}"
                           class="custom-icon complaints-action-btn complaints-action-delete delete-record"
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