<div class="tab-pane fade" id="navs-pills-justified-smtp_data" role="tabpanel">
    <div class="settings-card" data-card="smtp">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-mail-cog"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.smtp_settings') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.smtp_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-lock" aria-hidden="true"></i>
                {{ __('admin/main.sensitive_data') }}
            </span>
        </header>

        <form class="validated-form form" action="{{ route('admin.settings.update') }}" method="POST" novalidate>
            @csrf
            @method('put')
            <div class="settings-card__body">
                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-server" aria-hidden="true"></i>
                        {{ __('admin/main.server_credentials') }}
                    </div>
                    <div class="row g-3">
                        <x-form.text :options="['name' => 'mail_mailer',     'value' => isset($settings['mail_mailer'])     ? $settings['mail_mailer']     : 'smtp', 'label' => 'mail_mailer',     'class' => 'col-md-6']" />
                        <x-form.text :options="['name' => 'mail_host',       'value' => isset($settings['mail_host'])       ? $settings['mail_host']       : '',     'label' => 'mail_host',       'class' => 'col-md-6']" />
                        <x-form.text :options="['name' => 'mail_port',       'value' => isset($settings['mail_port'])       ? $settings['mail_port']       : '2525', 'label' => 'mail_port',       'class' => 'col-md-6']" />
                        <x-form.text :options="['name' => 'mail_encryption', 'value' => isset($settings['mail_encryption']) ? $settings['mail_encryption'] : 'tls',  'label' => 'mail_encryption', 'class' => 'col-md-6']" />
                    </div>
                </div>

                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-key" aria-hidden="true"></i>
                        {{ __('admin/main.authentication') }}
                    </div>
                    <div class="row g-3">
                        <x-form.text :options="['name' => 'mail_username', 'value' => isset($settings['mail_username']) ? $settings['mail_username'] : '', 'label' => 'mail_username', 'class' => 'col-md-6']" />
                        <x-form.password :options="['name' => 'mail_password', 'value' => isset($settings['mail_password']) ? $settings['mail_password'] : '', 'label' => 'mail_password', 'class' => 'col-md-6']" />
                    </div>
                </div>

                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-send" aria-hidden="true"></i>
                        {{ __('admin/main.sender_identity') }}
                    </div>
                    <div class="row g-3">
                        <x-form.email :options="['name' => 'mail_from_address', 'value' => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '', 'label' => 'mail_from_address', 'class' => 'col-md-6']" />
                        <x-form.text  :options="['name' => 'mail_from_name',    'value' => isset($settings['mail_from_name'])    ? $settings['mail_from_name']    : '', 'label' => 'mail_from_name',    'class' => 'col-md-6']" />
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
