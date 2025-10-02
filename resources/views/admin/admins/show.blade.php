@extends('admin.layouts.crud.show')

@push('header')
    <h5 class="mb-0">{{ __('admin/main.admin_details') }}</h5>
    <div>
        <a href="{{ route('admin.admins.edit', $id) }}" class="btn btn-success me-2"><i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}</a>
        <a href="#" data-id="{{ $admin->id }}" data-route="{{ route('admin.admins.destroy', ['admin' => $admin]) }}" class="btn btn-danger me-2 delete-record" title="Delete">
            <i class="ti ti-trash "></i> {{ __('admin/main.delete') }}
        </a>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}</a>
    </div>
@endpush

@push('content')
    <div class="row g-4">
        <div class="col-xl-4 col-md-5">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center p-4">
                    <div class="mx-auto mb-3" style="width: 148px; height: 148px; padding: 4px; background: linear-gradient(135deg, rgba(105,108,255,.15), rgba(105,108,255,.05)); border-radius: 50%;">
                        <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="rounded-circle border" style="width: 140px; height: 140px; object-fit: cover;" />
                    </div>
                    <h5 class="mb-1">{{ $admin->name }}</h5>
                    <div class="text-muted mb-3">{{ $admin->email }}</div>
                    <div class="d-flex justify-content-center flex-wrap gap-2 align-items-center">
                        <span class="badge status-badge {{ $admin->statusData()['class'] }}"
                              data-active-label="{{ __('admin/main.active') }}"
                              data-blocked-label="{{ __('admin/main.blocked') }}">{{ $admin->statusData()['label'] }}</span>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input switch-block" type="checkbox" role="switch"
                                   data-id="{{ $admin->id }}"
                                   data-route="{{ route('admin.admins.switchBlock', ['id' => $admin->id]) }}"
                                   {{ $admin->is_blocked ? 'checked' : '' }}
                                   title="{{ __('admin/main.blocked') }}" />
                            {{-- <label class="form-check-label ms-2">{{ __('admin/main.blocked') }}</label> --}}
                        </div>
                        @if($admin->type)
                            <span class="badge bg-label-primary">{{ $admin->type->label() }}</span>
                        @endif
                        @if(!empty($admin->role_name))
                            <span class="badge bg-label-info">{{ $admin->role_name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-7">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="mb-0">{{ __('admin/main.admin_details') }}</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">{{ __('admin/main.name') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $admin->name }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('admin/main.email') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $admin->email }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('admin/inputs.phone') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $admin->full_phone ?? ('+' . $admin->country_code . ' ' . $admin->phone) }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('admin/inputs.country_code') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $admin->country_code }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('admin/main.type') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $admin->type?->label() }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('admin/main.role') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $admin->role_name }}</dd>

                        <dt class="col-sm-4 text-muted">{{ __('admin/inputs.is_notify') }}</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $admin->is_notify ? __('admin/main.yes') : __('admin/main.no') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="{{ asset('style/admin/custom-js/admin-table.js') }}"></script>
@endpush