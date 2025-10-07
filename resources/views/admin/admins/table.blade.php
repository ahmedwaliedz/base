@extends('admin.layouts.crud.table')


@section('table')
    @foreach ($admins as $admin)
        <tr class="data-rows {{ $admin->deleted_at ? 'deleted-table-row' : '' }}">
            <td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $admin->id }}" data-id="{{ $admin->id }}"
                    class="dt-checkboxes form-check-input"></td>
            <td class="sorting_1">
                <div class="d-flex product-name">
                    <div class="avatar-wrapper">
                        <div class="avatar avatar me-2 rounded-2 bg-label-secondary"><img src="{{ $admin->image }}"
                                alt="Product-9" class="rounded-2">
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h6 class="text-body text-nowrap mb-2">{{ $admin->name }}</h6>
                        <span class="text-muted text-truncate d-block mb-1">
                            <i class="ti ti-phone"></i> : {{ $admin->phone }}
                        </span>
                        <span class="text-muted text-truncate d-block">
                            <i class="ti ti-mail"></i> : {{ $admin->email }}
                        </span>
                    </div>
                </div>
            </td>

            <td>
                {{ $admin->role_name }}
            </td>

            <td class="sorting_1">
                <div class="d-flex align-items-center gap-2 flex-nowrap">
                    <span class="badge  {{ $admin->statusData()['class'] }} status-badge"
                        text-capitalized="">{{ $admin->statusData()['label'] }}</span>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input switch-block" type="checkbox" role="switch"
                            data-id="{{ $admin->id }}"
                            data-route="{{ route('admin.admins.switchBlock', ['id' => $admin->id]) }}"
                            {{ !$admin->is_blocked ? 'checked' : '' }} title="{{ __('admin/main.blocked') }}" />
                    </div>
                </div>
            </td>

            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap">

                    <a href="{{ route('admin.admins.edit', ['admin' => $admin]) }}"
                        class="bg-success text-white custom-icon" data-bs-toggle="tooltip" data-placement="top"
                        title="@lang('admin/main.edit')">
                        <i class="ti ti-pencil"></i>
                    </a>

                    <a href="{{ route('admin.admins.show', ['admin' => $admin]) }}"
                        class="bg-primary text-white custom-icon" data-bs-toggle="tooltip" data-placement="top"
                        title="@lang('admin/main.show')">
                        <i class="ti ti-eye"></i>
                    </a>

                    <a data-bs-toggle="modal" data-bs-target="#notificationModal" data-id="{{ $admin->id }}"
                        class="send-notification bg-warning text-white custom-icon" data-bs-toggle="tooltip"
                        data-placement="top" title="@lang('admin/main.send_notification')">
                        <i class="ti ti-bell-plus"></i>
                    </a>

                    <a data-bs-toggle="modal" data-bs-target="#emailModal" class="bg-info text-white custom-icon"
                        data-bs-toggle="tooltip" data-placement="top" title="@lang('admin/main.send_email')">
                        <i class="ti ti-mail-plus"></i>
                    </a>

                    <a href="javascript:void(0);" data-id="{{ $admin->id }}"
                        data-route="{{ route('admin.admins.destroy', ['admin' => $admin]) }}"
                        class="bg-danger text-white custom-icon delete-row" data-bs-toggle="tooltip" data-placement="top"
                        title="@lang('admin/main.delete')">
                        <i class="ti ti-trash "></i>
                    </a>

                    @if ($admin->deleted_at)
                        <a href="javascript:void(0);" data-id="{{ $admin->id }}"
                            data-route="{{ route('admin.admins.restore', ['id' => $admin->id]) }}"
                            class="bg-success text-white custom-icon restore-row" data-bs-toggle="tooltip"
                            data-placement="top" title="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up "></i>
                        </a>
                    @endif

                </div>
            </td>
        </tr>
    @endforeach
@endsection
