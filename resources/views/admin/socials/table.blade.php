@extends('admin.layouts.crud.table', ['rows' => $socials, 'createRoute' => route('admin.socials.create')])

@section('table')
    @foreach ($socials as $social)
        <tr class="data-rows {{ method_exists($social, 'trashed') && $social->trashed() ? 'deleted-table-row' : '' }}" data-social-id="{{ $social->id }}">
            @if (!(method_exists($social, 'trashed') && $social->trashed()))<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $social->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td><div class="avatar-wrapper"><img src="{{ $social->image }}" class="rounded-2" alt="" style="width:36px;height:36px;object-fit:cover"></div></td>
            <td class="text-nowrap">{{ $social->link }}</td>
            <td>@if(!(method_exists($social, 'trashed') && $social->trashed()))<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $social->id }}" data-route="{{ route('admin.socials.switchIsActive', ['id' => $social->id]) }}" {{ $social->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap socials-row-actions">
                    <a href="{{ route('admin.socials.show', ['social' => $social]) }}"
                       class="custom-icon socials-action-btn socials-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!(method_exists($social, 'trashed') && $social->trashed()))
                        <a href="{{ route('admin.socials.edit', ['social' => $social]) }}"
                           class="custom-icon socials-action-btn socials-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (method_exists($social, 'trashed') && $social->trashed())
                        <a href="javascript:void(0);" data-id="{{ $social->id }}"
                           data-route="{{ route('admin.socials.restore', ['id' => $social->id]) }}"
                           class="custom-icon socials-action-btn socials-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $social->id }}"
                           data-route="{{ route('admin.socials.destroy', ['social' => $social]) }}"
                           class="custom-icon socials-action-btn socials-action-delete delete-record"
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