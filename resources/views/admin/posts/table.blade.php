@extends('admin.layouts.crud.table', ['rows' => $posts, 'createRoute' => route('admin.posts.create')])

@section('table')
    @foreach ($posts as $post)
        <tr class="data-rows {{ $post->deleted_at ? 'deleted-table-row' : '' }}" data-post-id="{{ $post->id }}">
            @if (!$post->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $post->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $post->image }}" class="rounded-2" alt=""></div></td>
            <td>{{ Str::limit($post->title, 50) }}</td>
            <td>@if(!$post->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $post->id }}" data-route="{{ route('admin.posts.switchIsActive', ['id' => $post->id]) }}" {{ $post->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td><div class="d-flex gap-2">
                <a href="{{ route('admin.posts.show', ['post' => $post]) }}" class="custom-icon"><i class="ti ti-eye"></i></a>
                @if(!$post->deleted_at)<a href="{{ route('admin.posts.edit', ['post' => $post]) }}" class="custom-icon"><i class="ti ti-pencil"></i></a>@endif
                @if($post->deleted_at)<a href="javascript:void(0);" data-id="{{ $post->id }}" data-route="{{ route('admin.posts.restore', ['id' => $post->id]) }}" class="custom-icon restore-row"><i class="ti ti-arrow-back-up"></i></a>@else<a href="javascript:void(0);" data-id="{{ $post->id }}" data-route="{{ route('admin.posts.destroy', ['post' => $post]) }}" class="custom-icon delete-record"><i class="ti ti-trash"></i></a>@endif
            </div></td>
        </tr>
    @endforeach
@endsection