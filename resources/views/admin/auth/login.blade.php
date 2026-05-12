@extends('admin.layouts.auth-master')

@push('css')
    <link rel="stylesheet" href="{{asset('style/admin/vendor/css/pages/page-auth.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/css/auth.css')}}"/>
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css')}}"/>
@endpush

@section('content')
    <div class="authentication-wrapper authentication-bg auth-page">

        {{-- ═══════════════════════════════════════════════
             FULL-SCREEN ANIMATED BACKGROUND
             Soft blobs · particle constellation · dot grid
        ═══════════════════════════════════════════════ --}}
        <div class="auth-bg-stage" aria-hidden="true">
            <canvas class="auth-canvas" id="authCanvas"></canvas>
            <div class="auth-blob auth-blob-1"></div>
            <div class="auth-blob auth-blob-2"></div>
            <div class="auth-blob auth-blob-3"></div>
            <div class="auth-dot-grid"></div>
        </div>

        {{-- Floating language + theme controls --}}
        <div class="auth-controls-row">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-md ti-language-katakana"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="{{route('admin.lang.change', ['lang' => 'en'])}}">
                            <span class="align-middle"><i class="fi fi-us me-2"></i>English</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('admin.lang.change', ['lang' => 'ar'])}}">
                            <span class="align-middle"><i class="fi fi-sa me-2"></i>&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Brand color picker --}}
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                   data-bs-toggle="dropdown"
                   data-bs-container="body"
                   data-bs-popper-config='{"strategy":"fixed"}'
                   aria-expanded="false"
                   aria-label="{{ __('admin/main.brand_color') }}"
                   title="{{ __('admin/main.brand_color') }}">
                    <i class="ti ti-md ti-palette"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end brand-color-picker">
                    <div class="brand-color-picker__title">{{ __('admin/main.brand_color') }}</div>
                    <div class="brand-color-grid">
                        <button type="button" class="brand-swatch" data-brand="violet"  style="background:#7367F0;color:#7367F0" title="Violet"  aria-label="Violet"></button>
                        <button type="button" class="brand-swatch" data-brand="ocean"   style="background:#3B82F6;color:#3B82F6" title="Ocean"   aria-label="Ocean"></button>
                        <button type="button" class="brand-swatch" data-brand="sky"     style="background:#06B6D4;color:#06B6D4" title="Sky"     aria-label="Sky"></button>
                        <button type="button" class="brand-swatch" data-brand="emerald" style="background:#10B981;color:#10B981" title="Emerald" aria-label="Emerald"></button>
                        <button type="button" class="brand-swatch" data-brand="magenta" style="background:#D946EF;color:#D946EF" title="Magenta" aria-label="Magenta"></button>
                        <button type="button" class="brand-swatch" data-brand="sunset"  style="background:#F97316;color:#F97316" title="Sunset"  aria-label="Sunset"></button>
                        <button type="button" class="brand-swatch" data-brand="slate"   style="background:#64748B;color:#64748B" title="Slate"   aria-label="Slate"></button>
                        <button type="button" class="brand-swatch" data-brand="onyx"    style="background:#1E1E2A;color:#1E1E2A;border:2px solid rgba(255,255,255,0.22)" title="Onyx" aria-label="Onyx"></button>
                    </div>
                </div>
            </div>

            <div class="nav-item dropdown-style-switcher dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i id="theme-icon" class="ti ti-md ti-moon"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li class="change-theme" data-theme="light">
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                        </a>
                    </li>
                    <li class="change-theme" data-theme="dark">
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Centered form --}}
        <div class="auth-stage-center">
            <div class="auth-card">
                @include('admin.auth.parts.form-header')
                @include('admin.auth.parts.form')
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/js/extended-ui-sweetalert2.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/submit-form.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/handel-error.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-validation-on-inputs.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-block.js')}}"></script>
    <script src="{{asset('style/admin/custom-js/error-handlers/show-un-authorize.js')}}"></script>

    <script>
    (function () {
        /* ═══════════════════════════════════════════════
           PARTICLE CONSTELLATION  (subtle, full-viewport)
        ═══════════════════════════════════════════════ */
        var canvas = document.getElementById('authCanvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var dpr = Math.max(1, window.devicePixelRatio || 1);
        var PARTICLE_COUNT = 55;
        var LINK_DIST      = 130;
        var SPEED          = 0.25;
        var particles      = [];

        function resize() {
            canvas.width  = canvas.offsetWidth  * dpr;
            canvas.height = canvas.offsetHeight * dpr;
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.scale(dpr, dpr);
        }
        resize();
        window.addEventListener('resize', resize);

        function rand(min, max) { return Math.random() * (max - min) + min; }
        function isLight() { return document.documentElement.classList.contains('light-style'); }

        for (var i = 0; i < PARTICLE_COUNT; i++) {
            particles.push({
                x: rand(0, canvas.offsetWidth),
                y: rand(0, canvas.offsetHeight),
                vx: rand(-SPEED, SPEED),
                vy: rand(-SPEED, SPEED),
                r: rand(1, 2),
                alpha: rand(0.18, 0.45)
            });
        }

        function frame() {
            var w = canvas.offsetWidth, h = canvas.offsetHeight;
            ctx.clearRect(0, 0, w, h);
            var color = isLight() ? '115,103,240' : '157,143,255';

            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0) p.x = w; else if (p.x > w) p.x = 0;
                if (p.y < 0) p.y = h; else if (p.y > h) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(' + color + ',' + p.alpha + ')';
                ctx.fill();

                for (var j = i + 1; j < particles.length; j++) {
                    var q = particles[j];
                    var dx = p.x - q.x, dy = p.y - q.y;
                    var dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < LINK_DIST) {
                        var op = (1 - dist / LINK_DIST) * 0.20;
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(q.x, q.y);
                        ctx.strokeStyle = 'rgba(' + color + ',' + op + ')';
                        ctx.lineWidth = 0.5;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(frame);
        }
        frame();

        /* ═══════════════════════════════════════════════
           CARD — subtle 3-D tilt on mouse-move
        ═══════════════════════════════════════════════ */
        var card = document.querySelector('.auth-card');
        if (card) {
            var zone = card.closest('.auth-stage-center') || card.parentElement;
            card.style.willChange = 'transform';
            card.style.transition = 'transform .08s ease, box-shadow .25s ease';

            zone.addEventListener('mousemove', function (e) {
                var rect = card.getBoundingClientRect();
                var dx = (e.clientX - (rect.left + rect.width  / 2)) / (rect.width  / 2);
                var dy = (e.clientY - (rect.top  + rect.height / 2)) / (rect.height / 2);
                card.style.transform =
                    'perspective(1000px) rotateY(' + (dx * 3.5) + 'deg) ' +
                    'rotateX(' + (-dy * 3) + 'deg) scale(1.008)';
            });
            zone.addEventListener('mouseleave', function () {
                card.style.transition = 'transform .4s ease, box-shadow .25s ease';
                card.style.transform  = 'perspective(1000px) rotateY(0) rotateX(0) scale(1)';
                setTimeout(function () {
                    card.style.transition = 'transform .08s ease, box-shadow .25s ease';
                }, 420);
            });
        }
    })();
    </script>
@endpush