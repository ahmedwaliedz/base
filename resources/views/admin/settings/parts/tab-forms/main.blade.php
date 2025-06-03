<div class="tab-pane fade active show" id="navs-pills-justified-main" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.settings.update')}}" method="POST" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <x-form.text    :options="['name' => 'name[ar]'   , 'value' => isset($settings['name']['ar'])     ? $settings['name']['ar']: ''    , 'label' => 'project_name_ar'  , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.text    :options="['name' => 'name[en]'   , 'value' => isset($settings['name']['en'])     ? $settings['name']['en']: ''    , 'label' => 'project_name_en'  , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.text    :options="['name' => 'phone'      , 'value' => isset($settings['phone'])          ? $settings['phone']     : ''    , 'label' => 'phone'            , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.text    :options="['name' => 'whatsapp'   , 'value' => isset($settings['whatsapp'])       ? $settings['whatsapp']  : ''    , 'label' => 'project_whatsapp' , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.email   :options="['name' => 'email'      , 'value' => isset($settings['email'])          ? $settings['email']     : ''    , 'label' => 'email'            , 'class' => 'col-md-6', 'isRequired' => true]"   />
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit"   class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>
