@extends('admin.layouts.crud.table', ['rows' => $contactMessages, 'createRoute' => null])

@section('table')
    @foreach ($contactMessages as $message)
        <tr class="data-rows {{ $message->deleted_at ? 'deleted-table-row' : '' }}" data-contact-message-id="{{ $message->id }}">
            @if (!$message->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $message->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $message->name }}</td>
            <td>{{ $message->email }}</td>
            <td>{{ Str::limit($message->subject, 30) }}</td>
            <td>@if(!$message->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $message->id }}" data-route="{{ route('admin.contact-messages.switchIsRead', ['id' => $message->id]) }}" {{ $message->is_read ? 'checked' : '' }}></div>@else<span class="text-muted">&mdash;</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap contact-messages-row-actions">
                    <a href="{{ route('admin.contact-messages.show', ['contact_message' => $message]) }}"
                       class="custom-icon contact-messages-action-btn contact-messages-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if ($message->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $message->id }}"
                           data-route="{{ route('admin.contact-messages.restore', ['id' => $message->id]) }}"
                           class="custom-icon contact-messages-action-btn contact-messages-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $message->id }}"
                           data-route="{{ route('admin.contact-messages.destroy', ['contact_message' => $message]) }}"
                           class="custom-icon contact-messages-action-btn contact-messages-action-delete delete-record"
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