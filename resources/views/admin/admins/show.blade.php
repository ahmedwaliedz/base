@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('admin/main.admin_details') }}</h5>
        <div>
            <a href="{{ route('admin.admins.edit', $id) }}" class="btn btn-primary me-2">{{ __('admin/main.edit') }}</a>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">{{ __('admin/main.back') }}</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="fw-semibold">{{ __('admin/main.id') }}:</h6>
                <p>{{ $admin->id ?? $id }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="fw-semibold">{{ __('admin/main.name') }}:</h6>
                <p>{{ $admin->name ?? '' }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="fw-semibold">{{ __('admin/main.email') }}:</h6>
                <p>{{ $admin->email ?? '' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="fw-semibold">{{ __('admin/main.role') }}:</h6>
                <p>{{ $admin->role->name ?? '' }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="fw-semibold">{{ __('admin/main.created_at') }}:</h6>
                <p>{{ $admin->created_at ?? '' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="fw-semibold">{{ __('admin/main.updated_at') }}:</h6>
                <p>{{ $admin->updated_at ?? '' }}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <form id="deleteAdminForm" method="POST" action="{{ route('admin.admins.destroy', $id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('admin/main.confirm_delete') }}')">
                        {{ __('admin/main.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
@endpush
