@extends('admin.layouts.crud.table', [
    'rows' => $countries,
    'createRoute' => route('admin.countries.create'),
])

@section('table')
    @foreach ($countries as $country)
        <tr class="data-rows countries-table-row {{ $country->deleted_at ? 'deleted-table-row' : '' }}"
            data-country-id="{{ $country->id }}">
            @if (! $country->deleted_at)
                <td class="dt-checkboxes-cell">
                    <input type="checkbox" value="{{ $country->id }}" data-id="{{ $country->id }}"
                           class="dt-checkboxes form-check-input"
                           aria-label="{{ __('admin/main.select_row', ['name' => $country->name]) }}">
                </td>
            @else
                <td></td>
            @endif

            <td>
                <div class="d-flex product-name align-items-center gap-2">
                    <div class="avatar-wrapper flex-shrink-0">
                        <div class="avatar rounded-2 countries-flag-avatar">
                            <img src="{{ $country->flag_url }}" alt="" class="rounded-2">
                        </div>
                    </div>
                    <div class="d-flex flex-column min-w-0">
                        <span class="fw-semibold text-truncate">{{ $country->name }}</span>
                        <span class="d-md-none text-muted small text-truncate">
                            {{ $country->code }}
                        </span>
                        <span class="d-md-none text-muted small">
                            {{ __('admin/main.regions') }}: {{ number_format($country->regions_count ?? 0) }}
                            · {{ __('admin/main.cities') }}: {{ number_format($country->cities_count ?? 0) }}
                        </span>
                    </div>
                </div>
            </td>

            <td class="d-none d-md-table-cell countries-meta-cell text-nowrap">
                {{ $country->code }}
            </td>

            <td class="d-none d-md-table-cell countries-count-cell text-center">
                <span class="countries-count-badge" title="{{ __('admin/main.regions') }}">{{ number_format($country->regions_count ?? 0) }}</span>
            </td>

            <td class="d-none d-md-table-cell countries-count-cell text-center">
                <span class="countries-count-badge" title="{{ __('admin/main.cities') }}">{{ number_format($country->cities_count ?? 0) }}</span>
            </td>

<td class="countries-status-cell text-center">
                @if (!$country->deleted_at)
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span class="badge bg-label-{{ $country->is_active ? 'success' : 'secondary' }} fs-tiny">
                            {{ $country->is_active ? __('admin/main.active') : __('admin/main.inactive') }}
                        </span>
                        <div class="form-check form-switch countries-active-switch mb-0 d-flex justify-content-center">
                            <input class="form-check-input switch-active" type="checkbox" role="switch"
                                   data-id="{{ $country->id }}"
                                   data-route="{{ route('admin.countries.switchActive', ['id' => $country->id]) }}"
                                   {{ $country->is_active ? 'checked' : '' }}
                                   title="{{ $country->is_active ? __('admin/main.set_inactive') : __('admin/main.set_active') }}"
                                   aria-label="{{ __('admin/main.is_active') }}">
                        </div>
                    </div>
                @else
                    <span class="text-muted small">—</span>
                @endif
            </td>

            <td class="countries-actions-cell">
                <div class="d-flex align-items-center gap-2 flex-nowrap countries-row-actions">

                    <a href="{{ route('admin.countries.show', ['country' => $country]) }}"
                       class="custom-icon admins-action-btn admins-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (! $country->deleted_at)
                        <a href="{{ route('admin.countries.edit', ['country' => $country]) }}"
                           class="custom-icon admins-action-btn admins-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($country->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $country->id }}"
                           data-route="{{ route('admin.countries.restore', ['id' => $country->id]) }}"
                           class="custom-icon admins-action-btn admins-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $country->id }}"
                           data-route="{{ route('admin.countries.destroy', ['country' => $country]) }}"
                           class="custom-icon admins-action-btn admins-action-delete delete-record"
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
