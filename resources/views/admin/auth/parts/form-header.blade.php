{{-- Logo with pulse rings --}}
<div class="auth-brand-wrap auth-animate-logo">
    <div class="auth-logo-ring-wrap">
        <div class="auth-logo-pulse" aria-hidden="true"></div>
        <div class="auth-logo-pulse" aria-hidden="true"></div>
        <div class="auth-logo-pulse" aria-hidden="true"></div>
        <div class="auth-logo-inner">
            <img src="{{cache()->get('settings')['logo']}}"
                 alt="{{cache()->get('settings')['name'][adminLang()]}}">
        </div>
    </div>
</div>

<h3 class="auth-title auth-animate-title">
    {{__('admin/auth.welcome_to', ['site_name' => cache()->get('settings')['name'][adminLang()]])}}
</h3>
<p class="auth-subtitle auth-animate-subtitle">
    {{__('admin/auth.sign_in_to_your_account')}}
</p>