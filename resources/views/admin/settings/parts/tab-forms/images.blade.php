<div class="tab-pane fade" id="navs-pills-justified-images" role="tabpanel">
    <div class="settings-card" data-card="images">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-photo"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.images') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.images_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-cloud-upload" aria-hidden="true"></i>
                {{ __('admin/main.image_assets') }}
            </span>
        </header>

        <form class="validated-form form" action="{{ route('admin.settings.update') }}" method="POST" novalidate>
            @csrf
            @method('put')
            <div class="settings-card__body">
                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-photo-edit" aria-hidden="true"></i>
                        {{ __('admin/main.brand_assets') }}
                    </div>
                    <div class="row g-4">
                        <x-form.image :options="['name' => 'logo',          'label' => 'logo',          'class' => 'col-md-4', 'value' => isset($settings['logo'])          ? $settings['logo']          : '']" />
                        <x-form.image :options="['name' => 'no_data_image', 'label' => 'no_data_image', 'class' => 'col-md-4', 'value' => isset($settings['no_data_image']) ? $settings['no_data_image'] : '']" />
                        <x-form.image :options="['name' => 'fav_icon',      'label' => 'fav_icon',      'class' => 'col-md-4', 'value' => isset($settings['fav_icon'])      ? $settings['fav_icon']      : '']" />
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
