@extends('admin.layouts.crud.table', ['rows' => $users])

@section('table')
    @foreach ($users as $user)
        <tr class="data-rows users-table-row {{ $user->deleted_at ? 'deleted-table-row' : '' }}">
            @if (!$user->deleted_at)
                <td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $user->id }}" data-id="{{ $user->id }}"
                        class="dt-checkboxes form-check-input"></td>
            @else
                <td></td>
            @endif
            <td class="sorting_1">
                <div class="d-flex product-name">
                    <div class="avatar-wrapper">
                        <div class="avatar avatar me-2 rounded-2 bg-label-secondary"><img src="{{ $user->image }}"
                                alt="Product-9" class="rounded-2">
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h6 class="text-body text-nowrap mb-2">{{ $user->name }}</h6>

                    </div>
                </div>
            </td>

            <td>
                {{ $user->phone }}
            </td>

            <td>
                {{ $user->email }}
            </td>



            <td class="sorting_1 users-status-cell">
                <div class="d-flex align-items-center gap-2 flex-nowrap users-status-wrap">
                    <span class="badge  {{ $user->statusData()['class'] }} status-badge"
                        text-capitalized="">{{ $user->statusData()['label'] }}</span>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input switch-block" type="checkbox" role="switch"
                            data-id="{{ $user->id }}"
                            data-route="{{ route('admin.users.switchBlock', ['id' => $user->id]) }}"
                            {{ !$user->is_blocked ? 'checked' : '' }} title="{{ __('admin/main.blocked') }}" />
                    </div>
                </div>
            </td>



            <td class="users-actions-cell">
                <div class="d-flex align-items-center gap-2 flex-nowrap users-row-actions">

                    <a href="{{ route('admin.users.edit', ['user' => $user]) }}"
                        class="custom-icon users-action-btn users-action-edit"
                        data-bs-toggle="tooltip" data-placement="top" title="@lang('admin/main.edit')">
                        <i class="ti ti-pencil"></i>
                    </a>

                    <a href="{{ route('admin.users.show', ['user' => $user]) }}"
                        class="custom-icon users-action-btn users-action-view"
                        data-bs-toggle="tooltip" data-placement="top" title="@lang('admin/main.show')">
                        <i class="ti ti-eye"></i>
                    </a>

                    <a data-bs-toggle="modal" data-bs-target="#notificationModal" data-id="{{ $user->id }}"
                        class="send-notification custom-icon users-action-btn users-action-notify" data-bs-toggle="tooltip"
                        data-placement="top" title="@lang('admin/main.send_notification')">
                        <i class="ti ti-bell-plus"></i>
                    </a>

                    <a data-bs-toggle="modal" data-bs-target="#emailModal"
                        class="custom-icon users-action-btn users-action-email"
                        data-bs-toggle="tooltip" data-placement="top" title="@lang('admin/main.send_email')">
                        <i class="ti ti-mail-plus"></i>
                    </a>



                    @if ($user->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $user->id }}"
                            data-route="{{ route('admin.users.restore', ['id' => $user->id]) }}"
                            class="custom-icon users-action-btn users-action-restore restore-row" data-bs-toggle="tooltip"
                            data-placement="top" title="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up "></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $user->id }}"
                            data-route="{{ route('admin.users.destroy', ['user' => $user]) }}"
                            class="custom-icon users-action-btn users-action-delete delete-row" data-bs-toggle="tooltip"
                            data-placement="top" title="@lang('admin/main.delete')">
                            <i class="ti ti-trash "></i>
                        </a>
                    @endif

                </div>
            </td>
        </tr>
    @endforeach
@endsection
