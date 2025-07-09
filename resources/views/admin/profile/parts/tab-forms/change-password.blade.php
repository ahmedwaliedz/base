<div class="tab-pane fade" id="navs-pills-justified-change-password" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.profile.update-password')}}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <x-form.password :options="['name' => 'current_password', 'label' => 'current_password', 'class' => 'col-md-12', 'isRequired' => true ]" />
            <x-form.password :options="['name' => 'password', 'label' => 'new_password' , 'class' => 'col-md-6', 'isRequired' => true  ]" />
            <x-form.password :options="['name' => 'password_confirmation', 'class' => 'col-md-6', 'isRequired' => true ]" />
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>

