@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/validation/form-validation.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/select2/select2.css')}}"/>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('admin/main.create_admin') }}</h5>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">{{ __('admin/main.back') }}</a>
    </div>
    <div class="card-body">
        <form id="createAdminForm" class="needs-validation" novalidate method="POST" action="{{ route('admin.admins.store') }}">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">{{ __('admin/main.name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">{{ __('admin/main.please_enter_name') }}</div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">{{ __('admin/main.email') }}</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">{{ __('admin/main.please_enter_valid_email') }}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">{{ __('admin/main.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">{{ __('admin/main.please_enter_password') }}</div>
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">{{ __('admin/main.confirm_password') }}</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    <div class="invalid-feedback">{{ __('admin/main.please_confirm_password') }}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="role" class="form-label">{{ __('admin/main.role') }}</label>
                    <select class="form-select" id="role" name="role_id" required>
                        <option value="">{{ __('admin/main.select_role') }}</option>
                        <!-- Roles will be populated here -->
                    </select>
                    <div class="invalid-feedback">{{ __('admin/main.please_select_role') }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">{{ __('admin/main.create') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('style/admin/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js')}}"></script>
@endpush
