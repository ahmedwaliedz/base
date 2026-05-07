<div class="tab-pane fade active show" id="navs-pills-justified-notification" role="tabpanel">
    <div class="settings-card" data-card="notif">
        <header class="settings-card__head">
            <span class="settings-card__icon" aria-hidden="true">
                <i class="ti ti-bell-ringing"></i>
            </span>
            <div>
                <h2 class="settings-card__title">{{ __('admin/main.send_notification') }}</h2>
                <p class="settings-card__desc">{{ __('admin/main.notif_push_desc') }}</p>
            </div>
            <span class="settings-card__head-meta">
                <i class="ti ti-broadcast" aria-hidden="true"></i>
                {{ __('admin/main.in_app') }}
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
                        <input type="hidden" class="notification_id" name="id" value="group">
                        <div class="col-md-6">
                            <label class="form-label" for="notif-class-push">{{ __('admin/inputs.notification_user_ype') }}</label>
                            <select class="form-select user-class-select" id="notif-class-push" name="class" required>
                                <option value="App\Models\User">{{ __('admin/main.users') }}</option>
                                <option value="App\Models\Admin">{{ __('admin/main.admins') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 notification-type-select">
                            <label class="form-label" for="notif-type-push">{{ __('admin/main.notification_user_status') }}</label>
                            <select name="user_type" id="notif-type-push" class="form-select notification-types">
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
