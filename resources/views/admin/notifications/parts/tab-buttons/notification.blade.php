<li role="presentation">
    <button type="button"
            class="settings-nav__btn active"
            role="tab"
            data-bs-toggle="tab"
            data-bs-target="#navs-pills-justified-notification"
            data-tab="notif"
            data-label="{{ __('admin/main.send_notification') }}"
            aria-controls="navs-pills-justified-notification"
            aria-selected="true">
        <span class="settings-nav__icon" aria-hidden="true">
            <i class="ti ti-bell-ringing"></i>
        </span>
        <span class="settings-nav__body">
            <span class="settings-nav__name">{{ __('admin/main.send_notification') }}</span>
            <span class="settings-nav__desc">{{ __('admin/main.notif_push_desc') }}</span>
        </span>
        <i class="ti ti-chevron-right settings-nav__chev" aria-hidden="true"></i>
    </button>
</li>
