<div class="tab-pane fade active show" id="navs-pills-justified-main" role="tabpanel">
    <div class="settings-card" data-card="main">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-id-badge-2"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.main_data') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.main_data_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-shield-check" aria-hidden="true"></i>
                {{ __('admin/main.public_info') }}
            </span>
        </header>

        <form class="validated-form form" action="{{ route('admin.settings.update') }}" method="POST" novalidate>
            @csrf
            @method('put')
            <div class="settings-card__body">
                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-building" aria-hidden="true"></i>
                        {{ __('admin/main.project_identity') }}
                    </div>
                    <div class="row g-3">
                        <x-form.text :options="['name' => 'name[ar]', 'value' => isset($settings['name']['ar']) ? $settings['name']['ar'] : '', 'label' => 'project_name_ar', 'class' => 'col-md-6', 'isRequired' => true]" />
                        <x-form.text :options="['name' => 'name[en]', 'value' => isset($settings['name']['en']) ? $settings['name']['en'] : '', 'label' => 'project_name_en', 'class' => 'col-md-6', 'isRequired' => true]" />
                    </div>
                </div>

                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-phone" aria-hidden="true"></i>
                        {{ __('admin/main.contact_channels') }}
                    </div>
                    <div class="row g-3">
                        <x-form.text  :options="['name' => 'phone',    'value' => isset($settings['phone'])    ? $settings['phone']    : '', 'label' => 'phone',            'class' => 'col-md-6', 'isRequired' => true]" />
                        <x-form.text  :options="['name' => 'whatsapp', 'value' => isset($settings['whatsapp']) ? $settings['whatsapp'] : '', 'label' => 'project_whatsapp', 'class' => 'col-md-6', 'isRequired' => true]" />
                        <x-form.email :options="['name' => 'email',    'value' => isset($settings['email'])    ? $settings['email']    : '', 'label' => 'email',            'class' => 'col-md-6', 'isRequired' => true]" />
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
