<div class="tab-pane fade" id="navs-pills-justified-location" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.settings.update')}}" method="POST" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <h5>{{ __('admin/main.location_settings') }}</h5>
            <x-form.text    :options="['name' => 'map_desc[ar]', 'value' => isset($settings['map_desc']['ar'])     ? $settings['map_desc']['ar']: ''    , 'label' => 'map_desc_ar'  , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.text    :options="['name' => 'map_desc[en]', 'value' => isset($settings['map_desc']['en'])     ? $settings['map_desc']['en']: ''    , 'label' => 'map_desc_en'  , 'class' => 'col-md-6', 'isRequired' => true]"   />
            <x-form.map :options="[
                'name'                  => 'location_map',
                'lat'                   => isset($settings['lat']) ? $settings['lat'] : '24.7136',
                'lng'                   => isset($settings['lng']) ? $settings['lng'] : '46.6753',
                'google_map_api_key'    => isset($settings['google_map_api_key']) ? $settings['google_map_api_key'] : '',
                'isRequired'            => true
            ]" />
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit"   class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>
