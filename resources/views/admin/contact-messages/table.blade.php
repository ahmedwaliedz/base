@extends('admin.layouts.crud.table', ['rows' => $contactMessages, 'createRoute' => null])

@section('table')
    @foreach ($contactMessages as $message)
        <tr class="data-rows {{ $message->deleted_at ? 'deleted-table-row' : '' }}" data-contact-message-id="{{ $message->id }}">
            @if (!$message->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $message->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ $message->name }}</td>
            <td>{{ $message->email }}</td>
            <td>{{ Str::limit($message->subject, 30) }}</td>
            <td>@if(!$message->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $message->id }}" data-route="{{ route('admin.contact-messages.switchIsRead', ['id' => $message->id]) }}" {{ $message->is_read ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.contact-messages.show', ['contact_message' => $message]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if($message->deleted_at)<a href="javascript:void(0);" data-id="{{ $message->id }}" data-route="{{ route('admin.contact-messages.restore', ['id' => $message->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $message->id }}" data-route="{{ route('admin.contact-messages.destroy', ['contact_message' => $message]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection