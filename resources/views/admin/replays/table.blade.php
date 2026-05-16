@extends('admin.layouts.crud.table', ['rows' => $replays, 'createRoute' => null])

@section('table')
    @foreach ($replays as $replay)
        <tr class="data-rows {{ $replay->deleted_at ? 'deleted-table-row' : '' }}" data-replay-id="{{ $replay->id }}">
            @if (!$replay->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $replay->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ Str::limit($replay->replay, 50) }}</td>
            <td><span class="badge bg-label-info">{{ class_basename($replay->replayable_type) }}</span></td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.replays.show', ['replay' => $replay]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if($replay->deleted_at)<a href="javascript:void(0);" data-id="{{ $replay->id }}" data-route="{{ route('admin.replays.restore', ['id' => $replay->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $replay->id }}" data-route="{{ route('admin.replays.destroy', ['replay' => $replay]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection