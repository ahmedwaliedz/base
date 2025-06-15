<div class="tab-pane fade" id="navs-pills-justified-smtp_data" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.settings.update')}}" method="POST" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <h5>{{ __('admin/main.smtp_settings') }}</h5>
            <x-form.text    :options="['name' => 'mail_mailer'      , 'value' => isset($settings['mail_mailer'])      ? $settings['mail_mailer']      : 'smtp' , 'label' => 'mail_mailer'      , 'class' => 'col-md-6']" />
            <x-form.text    :options="['name' => 'mail_host'        , 'value' => isset($settings['mail_host'])        ? $settings['mail_host']        : ''     , 'label' => 'mail_host'        , 'class' => 'col-md-6']" />
            <x-form.text    :options="['name' => 'mail_port'        , 'value' => isset($settings['mail_port'])        ? $settings['mail_port']        : '2525' , 'label' => 'mail_port'        , 'class' => 'col-md-6']" />
            <x-form.text    :options="['name' => 'mail_username'    , 'value' => isset($settings['mail_username'])    ? $settings['mail_username']    : ''     , 'label' => 'mail_username'    , 'class' => 'col-md-6']" />
            <x-form.text    :options="['name' => 'mail_password'    , 'value' => isset($settings['mail_password'])    ? $settings['mail_password']    : ''     , 'label' => 'mail_password'    , 'class' => 'col-md-6']" />
            <x-form.text    :options="['name' => 'mail_encryption'  , 'value' => isset($settings['mail_encryption'])  ? $settings['mail_encryption']  : 'tls'  , 'label' => 'mail_encryption'  , 'class' => 'col-md-6']" />
            <x-form.email   :options="['name' => 'mail_from_address', 'value' => isset($settings['mail_from_address'])? $settings['mail_from_address']: ''     , 'label' => 'mail_from_address', 'class' => 'col-md-6']" />
            <x-form.text    :options="['name' => 'mail_from_name'   , 'value' => isset($settings['mail_from_name'])   ? $settings['mail_from_name']   : ''     , 'label' => 'mail_from_name'   , 'class' => 'col-md-6']" />
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit"   class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>
