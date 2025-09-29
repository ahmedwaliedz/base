@extends('admin.layouts.crud.table')


@section('table')
    @foreach ($admins as $admin)
        <tr class="data-rows">
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
                <span class="badge  {{ $admin->statusData()['class'] }} "
                    text-capitalized="">{{ $admin->statusData()['label'] }}</span>
            </td>

            <td>
                <div class="d-inline-block text-nowrap">

                    <a href="{{ route('admin.admins.edit', ['admin' => $admin]) }}"
                        class="btn btn-sm btn-icon shadow-none">
                        <i class="text-primary ti ti-edit"></i>
                    </a>

                    <a href="{{ route('admin.admins.show', ['admin' => $admin]) }}"
                        class="btn btn-sm btn-icon shadow-none">
                        <i class="text-info ti ti-eye-check"></i>
                    </a>

                    <a data-bs-toggle="modal" data-bs-target="#notificationModal" data-id="{{ $admin->id }}"
                        class="send-notification btn btn-sm btn-icon shadow-none">
                        <i class="text-success ti ti-bell-star"></i>
                    </a>

                    <a data-bs-toggle="modal" data-bs-target="#notificationModal" class="btn btn-sm btn-icon shadow-none">
                        <i class="text-success ti ti-mail-share"></i>
                    </a>

                    <a data-id="{{ $admin->id }}" data-route="{{ route('admin.admins.destroy', ['admin' => $admin]) }}" class="btn btn-sm btn-icon delete-record shadow-none">
                        <i class="text-danger ti ti-trash delete-record"></i>
                    </a>

                </div>
            </td>
        </tr>
    @endforeach
@endsection
