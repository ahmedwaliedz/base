<div class="tab-pane fade active show" id="navs-pills-justified-pricing" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.settings.update')}}" method="POST" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <x-form.text   :options="['name' => 'app_commission'     , 'value' => isset($settings['app_commission'])     ? $settings['app_commission']     : ''    , 'label' => 'app_commission'       , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.text   :options="['name' => 'vat_ratio'          , 'value' => isset($settings['vat_ratio'])          ? $settings['vat_ratio']          : ''    , 'label' => 'vat_ratio'            , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.text   :options="['name' => 'coupon_max_ratio'   , 'value' => isset($settings['coupon_max_ratio'])   ? $settings['coupon_max_ratio']   : ''    , 'label' => 'coupon_max_ratio'     , 'class' => 'col-md-6', 'isRequired' => true]"   />

        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit"   class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>
