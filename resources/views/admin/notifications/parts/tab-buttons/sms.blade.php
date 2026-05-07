<li role="presentation">
    <button type="button"
            class="settings-nav__btn"
            role="tab"
            data-bs-toggle="tab"
            data-bs-target="#navs-pills-justified-sms"
            data-tab="sms"
            data-label="{{ __('admin/main.send_sms') }}"
            aria-controls="navs-pills-justified-sms"
            aria-selected="false">
        <span class="settings-nav__icon" aria-hidden="true">
            <i class="ti ti-message-2"></i>
        </span>
        <span class="settings-nav__body">
            <span class="settings-nav__name">{{ __('admin/main.send_sms') }}</span>
            <span class="settings-nav__desc">{{ __('admin/main.notif_sms_desc') }}</span>
        </span>
        <i class="ti ti-chevron-right settings-nav__chev" aria-hidden="true"></i>
    </button>
</li>
