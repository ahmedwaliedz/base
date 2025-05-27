<!-- Logo -->
<div class="app-brand mb-4">
    <a href="#" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">
            <img src="{{cache()->get('settings')['logo']}}" alt="{{cache()->get('settings')['name'][adminLang()]}}" class="w-100">
        </span>
    </a>
</div>

<h3 class="mb-1 fw-bold">{{__('admin/auth.welcome_to' , ['site_name' => cache()->get('settings')['name'][adminLang()]])}}</h3>
<p class="mb-4">{{__('admin/auth.sign_in_to_your_account')}}</p>
