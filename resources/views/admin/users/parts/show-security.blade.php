<div class="user-security">
    <div class="user-security__item">
        <i class="ti ti-{{ $user->email_verified_at ? 'circle-check' : 'circle-x' }}"></i>
        <div>
            <div class="user-security__title">{{ __('admin/main.email_verified') }}</div>
            <div class="user-security__sub">{{ $user->email_verified_at?->diffForHumans() ?? __('admin/main.not_verified_yet') }}</div>
        </div>
    </div>
    <div class="user-security__item">
        <i class="ti ti-{{ $user->phone_verified_at ? 'circle-check' : 'circle-x' }}"></i>
        <div>
            <div class="user-security__title">{{ __('admin/main.phone_verified') }}</div>
            <div class="user-security__sub">{{ $user->phone_verified_at?->diffForHumans() ?? __('admin/main.not_verified_yet') }}</div>
        </div>
    </div>
    <div class="user-security__item">
        <i class="ti ti-{{ $user->is_active ? 'circle-check' : 'circle-x' }}"></i>
        <div>
            <div class="user-security__title">{{ __('admin/main.is_active') }}</div>
            <div class="user-security__sub">{{ $user->is_active ? __('admin/main.yes') : __('admin/main.no') }}</div>
        </div>
    </div>
    <div class="user-security__item">
        <i class="ti ti-{{ $user->is_complete_info ? 'circle-check' : 'circle-x' }}"></i>
        <div>
            <div class="user-security__title">{{ __('admin/main.is_complete_info') }}</div>
            <div class="user-security__sub">{{ $user->is_complete_info ? __('admin/main.yes') : __('admin/main.no') }}</div>
        </div>
    </div>
</div>
