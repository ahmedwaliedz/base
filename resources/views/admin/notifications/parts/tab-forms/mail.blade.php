<div class="tab-pane fade" id="navs-pills-justified-mail" role="tabpanel">
    <div class="settings-card" data-card="mail">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-mail-fast"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.send_mail') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.notif_mail_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-at" aria-hidden="true"></i>
                {{ __('admin/main.email_channel') }}
            </span>
        </header>

        <form class="validated-form form" action="{{ route('admin.notifications.sendEmail') }}" method="POST" novalidate>
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
                        <input type="hidden" class="notification_id" name="id" value="group">
                        <input type="hidden" name="type" value="mail">
                        <div class="col-md-6">
                            <label class="form-label" for="notif-class-mail">{{ __('admin/inputs.user_type') }}</label>
                            <select class="form-select user-class-select" id="notif-class-mail" name="class" required>
                                <option value="App\Models\User">{{ __('admin/main.users') }}</option>
                                <option value="App\Models\Admin">{{ __('admin/main.admins') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 notification-type-select">
                            <label class="form-label" for="notif-type-mail">{{ __('admin/main.notification_user_status') }}</label>
                            <select name="user_type" id="notif-type-mail" class="form-select notification-types">
                                <option selected value="all">{{ __('admin/main.all') }}</option>
                                {{-- Populated dynamically by index.blade.php JS --}}
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
