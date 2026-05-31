@extends('admin.layouts.crud.table', ['rows' => $posts, 'createRoute' => route('admin.posts.create')])

@section('table')
    @foreach ($posts as $post)
        <tr class="data-rows {{ $post->deleted_at ? 'deleted-table-row' : '' }}" data-post-id="{{ $post->id }}">
            @if (!$post->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $post->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $post->image ?: asset('style/admin/img/placeholder.png') }}" class="rounded-2" alt="" style="width:36px;height:36px;object-fit:cover"></div></td>
            <td>{{ Str::limit($post->title, 50) }}</td>
            <td>@if(!$post->deleted_at)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $post->id }}" data-route="{{ route('admin.posts.switchIsActive', ['id' => $post->id]) }}" {{ $post->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap posts-row-actions">
                    <a href="{{ route('admin.posts.show', ['post' => $post]) }}"
                       class="custom-icon posts-action-btn posts-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$post->deleted_at)
                        <a href="{{ route('admin.posts.edit', ['post' => $post]) }}"
                           class="custom-icon posts-action-btn posts-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($post->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $post->id }}"
                           data-route="{{ route('admin.posts.restore', ['id' => $post->id]) }}"
                           class="custom-icon posts-action-btn posts-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $post->id }}"
                           data-route="{{ route('admin.posts.destroy', ['post' => $post]) }}"
                           class="custom-icon posts-action-btn posts-action-delete delete-record"
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