<div class="tab-pane fade" id="navs-pills-justified-location" role="tabpanel">
    <div class="settings-card" data-card="location">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-map-pin"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.location_settings') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.location_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-world" aria-hidden="true"></i>
                {{ __('admin/main.geo_data') }}
            </span>
        </header>

        <form class="validated-form form" action="{{ route('admin.settings.update') }}" method="POST" novalidate>
            @csrf
            @method('put')
            <div class="settings-card__body">
                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-text-caption" aria-hidden="true"></i>
                        {{ __('admin/main.location_description') }}
                    </div>
                    <div class="row g-3">
                        <x-form.text :options="['name' => 'map_desc[ar]', 'value' => isset($settings['map_desc']['ar']) ? $settings['map_desc']['ar'] : '', 'label' => 'map_desc_ar', 'class' => 'col-md-6', 'isRequired' => true]" />
                        <x-form.text :options="['name' => 'map_desc[en]', 'value' => isset($settings['map_desc']['en']) ? $settings['map_desc']['en'] : '', 'label' => 'map_desc_en', 'class' => 'col-md-6', 'isRequired' => true]" />
                    </div>
                </div>

                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-map-2" aria-hidden="true"></i>
                        {{ __('admin/main.location_on_map') }}
                    </div>
                    <div class="row g-3">
                        <x-form.map :options="[
                            'name'               => 'location_map',
                            'lat'                => isset($settings['lat']) ? $settings['lat'] : '24.7136',
                            'lng'                => isset($settings['lng']) ? $settings['lng'] : '46.6753',
                            'google_map_api_key' => isset($settings['google_map_api_key']) ? $settings['google_map_api_key'] : '',
                            'isRequired'         => true,
                        ]" />
                    </div>
                </div>
            </div>

            <footer class="settings-card__foot">
                <button type="submit" class="btn btn-primary waves-effect waves-light submit-button">
                    <i class="ti ti-device-floppy me-1" aria-hidden="true"></i>
                    {{ __('admin/main.save_changes') }}
                </button>
            </footer>
        </form>
    </div>
</div>
