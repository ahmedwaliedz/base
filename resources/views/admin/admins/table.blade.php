@extends('admin.layouts.crud.table', [
    'rows' => $admins,
    'createRoute' => route('admin.admins.create'),
])

@section('table')
    @foreach ($admins as $admin)
        @php
            $isSuper = $admin->type?->value === \App\Enums\AdminType::SUPER_ADMIN->value;
            $isProtected = $admin->id === 1;
        @endphp

        <tr class="data-rows admins-table-row {{ $admin->deleted_at ? 'deleted-table-row' : '' }} {{ $isSuper ? 'is-super-admin-row' : '' }}"
            data-admin-id="{{ $admin->id }}">

            @if (!$admin->deleted_at && !$isProtected)
                <td class="dt-checkboxes-cell">
                    <input type="checkbox" value="{{ $admin->id }}" data-id="{{ $admin->id }}"
                           class="dt-checkboxes form-check-input"
                           aria-label="{{ __('admin/main.select_row', ['name' => $admin->name]) }}">
                </td>
            @else
            @endif

            <td class="d-none d-md-table-cell admins-id-cell text-muted small text-nowrap">
                {{ $admin->id }}
            </td>

            <td>
                <div class="d-flex product-name align-items-center gap-2">
                    <div class="avatar-wrapper flex-shrink-0 position-relative">
                        <div class="avatar rounded-2">
                            <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="rounded-2">
                        </div>
                        @if ($isSuper)
                            <span class="admin-crown" title="{{ __('admin/main.super_admin') }}">
                                <i class="ti ti-crown"></i>
                            </span>
                        @endif
                    </div>
                    <div class="d-flex flex-column min-w-0">
                        <span class="admin-name fw-semibold text-truncate">{{ $admin->name }}</span>
                        <span class="admin-id-mobile d-md-none text-muted small">#{{ $admin->id }}</span>
                        <span class="admin-email-mobile d-md-none text-muted small text-truncate">{{ $admin->email }}</span>
                    </div>
                </div>
            </td>

            <td class="d-none d-md-table-cell admins-meta-cell text-truncate" style="max-width: 14rem;">
                {{ $admin->email ?: '—' }}
            </td>

            <td class="d-none d-md-table-cell admins-meta-cell text-nowrap">
                @php
                    $phoneDisplay = $admin->full_phone ?: $admin->phone;
                @endphp
                {{ $phoneDisplay ?: '—' }}
            </td>

            <td class="d-none d-lg-table-cell admins-meta-cell text-nowrap">
                {{ $admin->country_code ?: '—' }}
            </td>

            <td class="d-none d-md-table-cell admins-role-cell">
                <div class="d-flex flex-column gap-1 align-items-start">
                    <span class="admin-role-badge">
                        <i class="ti ti-shield-check" aria-hidden="true"></i>{{ $admin->role_name }}
                    </span>
                    @if ($admin->type)
                        <span class="admin-type-badge admin-type-badge--{{ $isSuper ? 'super' : 'regular' }}">
                            @if ($isSuper)<i class="ti ti-crown" aria-hidden="true"></i>@endif
                            {{ $admin->type->label() }}
                        </span>
                    @endif
                </div>
            </td>

            <td class="d-none d-lg-table-cell admins-notify-cell">
                <span class="admin-notify-pill {{ $admin->is_notify ? 'is-on' : 'is-off' }}"
                      title="{{ $admin->is_notify ? __('admin/main.notify_on') : __('admin/main.notify_off') }}">
                    <i class="ti {{ $admin->is_notify ? 'ti-bell' : 'ti-bell-off' }}" aria-hidden="true"></i>
                    {{ $admin->notify_label }}
                </span>
            </td>

            <td class="d-none d-lg-table-cell admins-meta-cell text-nowrap small">
                {{ $admin->deleted_at?->format('Y-m-d H:i') ?? '—' }}
            </td>

            <td class="admins-status-cell">
                <div class="admins-status-wrap">
                    @php
                        $isBlocked = $admin->is_blocked;
                        $statusLabel = $admin->statusData()['label'];
                    @endphp
                    <label class="admin-status-toggle"
                           title="{{ $isBlocked ? __('admin/main.unblock') : __('admin/main.block') }}">
                        <input class="form-check-input switch-block visually-hidden" type="checkbox" role="switch"
                               data-id="{{ $admin->id }}"
                               data-route="{{ route('admin.admins.switchBlock', ['id' => $admin->id]) }}"
                               data-active-label="{{ __('admin/main.active') }}"
                               data-blocked-label="{{ __('admin/main.blocked') }}"
                               {{ !$isBlocked ? 'checked' : '' }}
                               aria-label="{{ $isBlocked ? __('admin/main.click_to_unblock') : __('admin/main.click_to_block') }}">
                        <span class="admin-status-pill status-badge {{ $isBlocked ? 'is-blocked' : 'is-active' }}"
                              data-active-label="{{ __('admin/main.active') }}"
                              data-blocked-label="{{ __('admin/main.blocked') }}">
                            <span class="admin-status-pill__dot" aria-hidden="true"></span>
                            {{ $statusLabel }}
                        </span>
                    </label>
                </div>
            </td>

            <td class="admins-actions-cell">
                <div class="d-flex align-items-center gap-2 flex-nowrap admins-row-actions">

                    <a href="{{ route('admin.admins.show', ['admin' => $admin]) }}"
                       class="custom-icon admins-action-btn admins-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')">
                        <i class="ti ti-eye"></i>
                    </a>

                    @if (!$admin->deleted_at)
                        <a href="{{ route('admin.admins.edit', ['admin' => $admin]) }}"
                           class="custom-icon admins-action-btn admins-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')">
                            <i class="ti ti-pencil"></i>
                        </a>
                    @endif

                    @if ($admin->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $admin->id }}"
                           data-route="{{ route('admin.admins.restore', ['id' => $admin->id]) }}"
                           class="custom-icon admins-action-btn admins-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up"></i>
                        </a>
                    @elseif (!$isProtected)
                        <a href="javascript:void(0);" data-id="{{ $admin->id }}"
                           data-route="{{ route('admin.admins.destroy', ['admin' => $admin]) }}"
                           class="custom-icon admins-action-btn admins-action-delete delete-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.delete')">
                            <i class="ti ti-trash"></i>
                        </a>
                    @else
                        <span class="custom-icon admins-action-btn admins-action-locked"
                              data-bs-toggle="tooltip" data-bs-placement="top"
                              title="@lang('admin/main.protected_super_admin')">
                            <i class="ti ti-lock"></i>
                        </span>
                    @endif

                    @if (!$admin->deleted_at)
                        <div class="dropdown admin-more-dropdown">
                            <button type="button"
                                    class="custom-icon admins-action-btn admins-action-more"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="@lang('admin/main.more_actions')">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end admin-more-menu">
                                <li>
                                    <button type="button" class="dropdown-item send-notification"
                                            data-bs-toggle="modal"
                                            data-bs-target="#notificationModal"
                                            data-id="{{ $admin->id }}">
                                        <i class="ti ti-bell-plus me-2"></i>@lang('admin/main.send_notification')
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#emailModal"
                                            data-id="{{ $admin->id }}">
                                        <i class="ti ti-mail-plus me-2"></i>@lang('admin/main.send_email')
                                    </button>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
@endsection
