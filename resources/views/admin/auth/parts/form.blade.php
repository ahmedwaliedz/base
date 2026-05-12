<form class="validated-form form auth-login-form" action="{{route('admin.login')}}" method="POST" novalidate>
    @csrf

    {{-- Email field --}}
    <div class="auth-field form-group auth-animate-field" style="--i:0">
        <i class="ti ti-mail auth-field-icon" aria-hidden="true"></i>
        <input
            type="email"
            name="email"
            id="loginEmail"
            class="auth-field-input"
            placeholder=" "
            value="{{ old('email') }}"
            required
            autocomplete="email"
        />
        <label class="auth-field-label" for="loginEmail">
            {{__('admin/inputs.email')}}
        </label>
        <div class="help-block"></div>
    </div>

    {{-- Password field --}}
    <div class="auth-field form-group auth-animate-field" style="--i:1">
        <i class="ti ti-lock auth-field-icon" aria-hidden="true"></i>
        <input
            type="password"
            name="password"
            id="loginPassword"
            class="auth-field-input"
            placeholder=" "
            required
            autocomplete="current-password"
        />
        <label class="auth-field-label" for="loginPassword">
            {{__('admin/inputs.password')}}
        </label>
        <button type="button" class="auth-eye-toggle" id="authEyeToggle"
                aria-label="Toggle password visibility">
            <i class="ti ti-eye" id="authEyeIcon"></i>
        </button>
        <div class="help-block"></div>
    </div>

    {{-- Remember me --}}
    <div class="auth-remember auth-animate-field" style="--i:2">
        <label class="auth-check-label">
            <input type="checkbox" name="remember-me" value="1"
                   class="auth-check-input" id="rememberMe"/>
            <span class="auth-check-box" aria-hidden="true"></span>
            {{__('admin/auth.remember_me')}}
        </label>
    </div>

    {{-- Submit button --}}
    <div class="auth-animate-field" style="--i:3">
        <button type="submit" class="auth-submit-btn" id="authSubmitBtn">
            <span class="auth-btn-shimmer" aria-hidden="true"></span>
            <span class="auth-submit-text">{{__('admin/auth.login')}}</span>
            <span class="auth-submit-arrow" aria-hidden="true">
                <i class="ti ti-arrow-right"></i>
            </span>
            <span class="auth-spin-dot" aria-hidden="true"></span>
        </button>
    </div>

</form>

<script>
(function () {
    /* — Password eye toggle ——————————————————— */
    var eyeBtn   = document.getElementById('authEyeToggle');
    var eyeIcon  = document.getElementById('authEyeIcon');
    var pwdInput = document.getElementById('loginPassword');
    if (eyeBtn && pwdInput) {
        eyeBtn.addEventListener('click', function () {
            var isText = pwdInput.type === 'text';
            pwdInput.type = isText ? 'password' : 'text';
            eyeIcon.className = isText ? 'ti ti-eye' : 'ti ti-eye-off';
        });
    }

    /* — Card shake on validation error ——————— */
    document.addEventListener('validation-shown', function () {
        var card = document.querySelector('.auth-card');
        if (card) {
            card.classList.add('has-error');
            card.addEventListener('animationend', function () {
                card.classList.remove('has-error');
            }, { once: true });
        }
    });
})();
</script>