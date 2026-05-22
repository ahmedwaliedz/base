@extends('admin.layouts.crud.table', ['rows' => $seo, 'createRoute' => route('admin.seo.create')])

@section('table')
    @foreach ($seo as $seo)
        <tr class="data-rows {{ $seo->deleted_at ? 'deleted-table-row' : '' }}" data-seo-id="{{ $seo->id }}">
            @if (!$seo->deleted_at)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $seo->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ Str::limit($seo->meta_title, 40) }}</td>
            <td>{{ Str::limit($seo->meta_description, 60) }}</td>
            <td><span class="badge bg-label-info">{{ $seo->seoable_type ? class_basename($seo->seoable_type) : '-' }}</span></td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap seo-row-actions">
                    <a href="{{ route('admin.seo.show', ['seo' => $seo]) }}"
                       class="custom-icon seo-action-btn seo-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$seo->deleted_at)
                        <a href="{{ route('admin.seo.edit', ['seo' => $seo]) }}"
                           class="custom-icon seo-action-btn seo-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($seo->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $seo->id }}"
                           data-route="{{ route('admin.seo.restore', ['id' => $seo->id]) }}"
                           class="custom-icon seo-action-btn seo-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $seo->id }}"
                           data-route="{{ route('admin.seo.destroy', ['seo' => $seo]) }}"
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