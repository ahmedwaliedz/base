@extends('admin.layouts.crud.table', ['rows' => $seo, 'createRoute' => route('admin.seo.create')])

@section('table')
    @foreach ($seo as $item)
        <tr class="data-rows {{ $item->deleted_at ? 'deleted-table-row' : '' }}" data-seo-id="{{ $item->id }}">
            @if (!$item->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $item->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $item->image ?: asset('style/admin/img/placeholder.png') }}" class="rounded-2" alt="" style="width:36px;height:36px;object-fit:cover"></div></td>
            <td>{{ Str::limit($item->meta_title, 40) }}</td>
            <td>{{ Str::limit($item->meta_description, 60) }}</td>
            <td><span class="badge bg-label-info">{{ $item->seoable_type ? class_basename($item->seoable_type) : '-' }}</span></td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap seo-row-actions">
                    <a href="{{ route('admin.seo.show', ['seo' => $item]) }}"
                       class="custom-icon seo-action-btn seo-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$item->deleted_at)
                        <a href="{{ route('admin.seo.edit', ['seo' => $item]) }}"
                           class="custom-icon seo-action-btn seo-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($item->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $item->id }}"
                           data-route="{{ route('admin.seo.restore', ['id' => $item->id]) }}"
                           class="custom-icon seo-action-btn seo-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $item->id }}"
                           data-route="{{ route('admin.seo.destroy', ['seo' => $item]) }}"
                           class="custom-icon seo-action-btn seo-action-delete delete-record"
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