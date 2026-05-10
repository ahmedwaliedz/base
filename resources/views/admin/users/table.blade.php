@extends('admin.layouts.crud.table', [
    'rows'        => $users,
    'createRoute' => route('admin.users.create'),
])

@section('table')
    @foreach ($users as $user)
        <tr class="data-rows users-table-row {{ $user->deleted_at ? 'deleted-table-row' : '' }}"
            data-user-id="{{ $user->id }}">
            @if (!$user->deleted_at)
                <td class="dt-checkboxes-cell">
                    <input type="checkbox" value="{{ $user->id }}" data-id="{{ $user->id }}"
                           class="dt-checkboxes form-check-input"
                           aria-label="{{ __('admin/main.select_row', ['name' => $user->name]) }}">
                </td>
            @else
                <td></td>
            @endif

            {{-- Compact name + email cell on mobile, separated columns on desktop --}}
            <td>
                <div class="d-flex product-name align-items-center gap-2">
                    <div class="avatar-wrapper flex-shrink-0">
                        <div class="avatar rounded-2">
                            <img src="{{ $user->image }}" alt="{{ $user->name }}" class="rounded-2">
                        </div>
                    </div>
                    <div class="d-flex flex-column min-w-0">
                        <span class="user-name fw-semibold text-truncate">{{ $user->name }}</span>
                        <span class="user-email-mobile d-md-none text-muted small text-truncate">
                            {{ $user->email }}
                        </span>
                    </div>
                </div>
            </td>

            <td class="d-none d-md-table-cell">
                {{ $user->phone ?? '—' }}
            </td>

            <td class="d-none d-md-table-cell">
                {{ $user->email ?? '—' }}
            </td>

            <td class="users-status-cell">
                <div class="users-status-wrap">
                    @php
                        $isBlocked = $user->is_blocked;
                        $statusLabel = $user->statusData()['label'];
                    @endphp
                    <label class="user-status-toggle"
                           title="{{ $isBlocked ? __('admin/main.unblock') : __('admin/main.block') }}">
                        <input class="form-check-input switch-block visually-hidden" type="checkbox" role="switch"
                               data-id="{{ $user->id }}"
                               data-route="{{ route('admin.users.switchBlock', ['id' => $user->id]) }}"
                               data-active-label="{{ __('admin/main.active') }}"
                               data-blocked-label="{{ __('admin/main.blocked') }}"
                               {{ !$isBlocked ? 'checked' : '' }}
                               aria-label="{{ $isBlocked ? __('admin/main.click_to_unblock') : __('admin/main.click_to_block') }}">
                        <span class="user-status-pill status-badge {{ $isBlocked ? 'is-blocked' : 'is-active' }}"
                              data-active-label="{{ __('admin/main.active') }}"
                              data-blocked-label="{{ __('admin/main.blocked') }}">
                            <span class="user-status-pill__dot" aria-hidden="true"></span>
                            {{ $statusLabel }}
                        </span>
                    </label>
                </div>
            </td>

            <td class="users-actions-cell">
                <div class="d-flex align-items-center gap-2 flex-nowrap users-row-actions">
                    {{-- Primary actions: View, Edit, Delete/Restore --}}
                    <a href="{{ route('admin.users.show', ['user' => $user]) }}"
                       class="custom-icon users-action-btn users-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$user->deleted_at)
                        <a href="{{ route('admin.users.edit', ['user' => $user]) }}"
                           class="custom-icon users-action-btn users-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($user->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $user->id }}"
                           data-route="{{ route('admin.users.restore', ['id' => $user->id]) }}"
                           class="custom-icon users-action-btn users-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $user->id }}"
                           data-route="{{ route('admin.users.destroy', ['user' => $user]) }}"
                           class="custom-icon users-action-btn users-action-delete delete-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.delete')"
                           aria-label="@lang('admin/main.delete')">
                            <i class="ti ti-trash" aria-hidden="true"></i>
                        </a>
                    @endif

                    {{-- Secondary actions in a "more" dropdown --}}
                    @if (!$user->deleted_at)
                        <div class="dropdown user-more-dropdown">
                            <button type="button"
                                    class="custom-icon users-action-btn users-action-more"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="@lang('admin/main.more_actions')">
                                <i class="ti ti-dots-vertical" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end user-more-menu">
                                <li>
                                    <button type="button"
                                            class="dropdown-item send-notification"
                                            data-bs-toggle="modal"
                                            data-bs-target="#notificationModal"
                                            data-id="{{ $user->id }}">
                                        <i class="ti ti-bell-plus me-2" aria-hidden="true"></i>
                                        @lang('admin/main.send_notification')
                                    </button>
                                </li>
                                <li>
                                    <button type="button"
                                            class="dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#emailModal"
                                            data-id="{{ $user->id }}">
                                        <i class="ti ti-mail-plus me-2" aria-hidden="true"></i>
                                        @lang('admin/main.send_email')
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
