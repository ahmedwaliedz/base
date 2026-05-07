<li role="presentation">
    <button type="button"
            class="settings-nav__btn"
            role="tab"
            data-bs-toggle="tab"
            data-bs-target="#navs-pills-justified-mail"
            data-tab="mail"
            data-label="{{ __('admin/main.send_mail') }}"
            aria-controls="navs-pills-justified-mail"
            aria-selected="false">
        <span class="settings-nav__icon" aria-hidden="true">
            <i class="ti ti-mail-fast"></i>
        </span>
        <span class="settings-nav__body">
            <span class="settings-nav__name">{{ __('admin/main.send_mail') }}</span>
            <span class="settings-nav__desc">{{ __('admin/main.notif_mail_desc') }}</span>
        </span>
        <i class="ti ti-chevron-right settings-nav__chev" aria-hidden="true"></i>
    </button>
</li>
