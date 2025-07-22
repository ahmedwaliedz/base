<div class="tab-pane fade active show" id="navs-pills-justified-main-data" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.profile.update')}}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <x-form.image   :options="['name' => 'image',         'value' => $profile->image_url,     'label' => 'image',         'class' => 'col-md-12']" />
            <x-form.text    :options="['name' => 'name',          'value' => $profile->name,          'label' => 'name',          'class' => 'col-md-6', 'isRequired' => true]" />
            <x-form.text    :options="['name' => 'phone',         'value' => $profile->phone,         'label' => 'phone',         'class' => 'col-md-5', 'isRequired' => true]" />
            <x-form.select  :options="['name' => 'country_code',  'value' => $profile->country_code,  'label' => 'country_code',  'class' => 'col-md-1', 'isRequired' => true, 'options' => $countries]" />
            <x-form.email   :options="['name' => 'email',         'value' => $profile->email,         'label' => 'email',         'class' => 'col-md-6', 'isRequired' => true]" />
            <x-form.select  :options="['name' => 'is_notify',     'value' => $profile->is_notify,     'label' => 'is_notify',     'class' => 'col-md-6', 'options' => [['id' => true, 'name' => __('admin/main.yes')], ['id' => false, 'name' => __('admin/main.no')]]]" />
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>
