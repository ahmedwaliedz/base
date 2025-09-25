<form class="mb-3 validated-form form" novalidate method="POST" action="{{ route('admin.admins.update', $id) }}"
    enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="row g-3">
        <x-form.image :options="['name' => 'image', 'label' => 'image', 'class' => 'col-md-12', 'value' => $admin->image]" />

        <x-form.text :options="[
            'name' => 'name',
            'label' => 'name',
            'class' => 'col-md-6',
            'isRequired' => true,
            'value' => $admin->name,
        ]" />
        <x-form.number :options="[
            'name' => 'phone',
            'label' => 'phone',
            'class' => 'col-md-4',
            'isRequired' => true,
            'minLength' => 9,
            'maxLength' => 15,
            'value' => $admin->phone,
        ]" />
        <x-form.select :options="[
            'name' => 'country_code',
            'label' => 'country_code',
            'class' => 'col-md-2',
            'isRequired' => true,
            'options' => $countries,
            'value' => $admin->country_code,
        ]" />
        <x-form.email :options="[
            'name' => 'email',
            'label' => 'email',
            'class' => 'col-md-6',
            'isRequired' => true,
            'value' => $admin->email,
        ]" />
        <x-form.password :options="[
            'name' => 'password',
            'label' => 'password',
            'class' => 'col-md-6',
            'isRequired' => false,
        ]" />
        <x-form.select :options="[
            'name' => 'type',
            'label' => 'type',
            'class' => 'col-md-4',
            'isRequired' => true,
            'options' => $types,
            'value' => $admin->type,
        ]" />
        <x-form.select :options="[
            'name' => 'role_id',
            'label' => 'role',
            'class' => 'col-md-4',
            'options' => $roles,
            'isRequired' => true,
            'value' => $admin->role_id,
        ]" />
        <x-form.select :options="[
            'name' => 'is_notify',
            'value' => true,
            'label' => 'receive_notifications',
            'class' => 'col-md-4',
            'options' => $receiveNotificationsOptions,
            'value' => $admin->is_notify,
        ]" />
    </div>

    <div class="pt-4 d-flex justify-content-center mt-3">
        <button type="submit"
            class="btn btn-success me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
    </div>
</form>
