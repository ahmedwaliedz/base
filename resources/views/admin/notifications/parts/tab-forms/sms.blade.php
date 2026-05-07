<div class="tab-pane fade" id="navs-pills-justified-sms" role="tabpanel">
    <div class="settings-card" data-card="sms">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-message-2"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.send_sms') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.notif_sms_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-device-mobile-message" aria-hidden="true"></i>
                {{ __('admin/main.sms_channel') }}
            </span>
        </header>

        <form class="validated-form form" action="{{ route('admin.notifications.sendNotifications') }}" method="POST" novalidate>
            @csrf

            <div class="settings-card__body">
                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-message-circle" aria-hidden="true"></i>
                        {{ __('admin/main.notif_message_section') }}
                    </div>
                    <div class="row g-3">
                        <x-form.text-area :options="['name' => 'message[ar]', 'label' => 'message_ar', 'class' => 'col-md-12', 'isRequired' => true]" />
                        <x-form.text-area :options="['name' => 'message[en]', 'label' => 'message_en', 'class' => 'col-md-12', 'isRequired' => true]" />
                    </div>
                </div>

                <div class="settings-card__section">
                    <div class="settings-card__section-title">
                        <i class="ti ti-target-arrow" aria-hidden="true"></i>
                        {{ __('admin/main.notif_audience_section') }}
                    </div>
                    <div class="row g-3">
                        <input type="hidden" name="type" value="sms">
                        <input type="hidden" name="class" value="App\Models\User">
                        <div class="col-md-6">
                            <label class="form-label" for="notif-user-type-sms">{{ __('admin/inputs.user_type') }}</label>
                            <select class="form-select" id="notif-user-type-sms" name="user_type" required>
                                <option value="users">{{ __('admin/main.users') }}</option>
                                <option value="admins">{{ __('admin/main.admins') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="settings-card__foot">
                <button type="submit" class="btn btn-primary waves-effect waves-light submit-button">
                    <i class="ti ti-send me-1" aria-hidden="true"></i>
                    {{ __('admin/main.send') }}
                </button>
            </footer>
        </form>
    </div>
</div>
