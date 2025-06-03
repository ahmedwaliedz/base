<!DOCTYPE html>
@php
    use Illuminate\Support\Facades\Session;

    $lang = adminLang();
    app()->setLocale($lang);
    // Get theme preference from session or cookie if available
    $theme = Session::get('theme', 'theme-default');
@endphp

<html lang="{{$lang}}"
      class="layout-navbar-fixed layout-menu-fixed loaded"
      dir="{{adminDirection()}}"
      data-theme="{{ $theme }}"
      data-assets-path="{{asset('style/admin/')}}/"
      data-template="vertical-menu-template"
>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ __('admin/main.not_found') }} - 404</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/uploads/settings/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/css/pages/page-misc.css') }}" />
    <script src="{{ asset('style/admin/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('style/admin/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('style/admin/js/config.js') }}"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <link rel="stylesheet" href="{{ asset('style/admin/css/error-pages.css') }}" />
</head>
<body>
<div class="theme-toggle-wrapper">
    <button id="theme-toggle" class="theme-toggle" title="{{ __('admin/main.toggle_theme') }}" aria-label="{{ __('admin/main.toggle_theme') ?? 'Toggle light/dark theme' }}">
        <i class="ti ti-sun-filled light-icon" aria-hidden="true"></i>
        <i class="ti ti-moon-filled dark-icon" aria-hidden="true"></i>
    </button>
</div>
<div class="dots-container" id="dotsContainer"></div>

<div class="container-xxl py-4">
    <div class="misc-wrapper text-center">
        <div class="error-content mx-auto" style="max-width: 500px;">
            <h2 class="mb-3">{{ __('admin/main.page_not_found') }}</h2>
            <p class="mb-4">{{ __('admin/main.page_not_exist') }}</p>
        </div>
        <div class="position-relative">
            <div class="lottie-container" style="width: 350px; height: 350px; margin: 0 auto; position: relative; filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.1));">
                <lottie-player
                    src="{{ asset('storage/uploads/settings/404.json') }}"
                    background="transparent"
                    speed="0.5"
                    style="width: 100%; height: 100%; position: relative; z-index: 2;"
                    loop
                    autoplay
                    aria-label="{{ __('admin/main.not_found_animation') ?? 'Page not found animation' }}"
                    role="img">
                </lottie-player>
                <span class="visually-hidden">{{ __('admin/main.not_found_animation_desc') ?? 'An animation illustrating that the page was not found' }}</span>
            </div>
        </div>
        <a href="{{ route('admin.home') }}" class="mt-4 home-btn" id="homeButton" aria-label="{{ __('admin/main.back_to_home') ?? 'Back to home page' }}" data-redirect-text="{{ __('admin/main.redirecting') }}">
            <i class="ti ti-home-2 me-2" aria-hidden="true"></i>{{ __('admin/main.back_to_home') }}
        </a>
    </div>
</div>
<script src="{{ asset('style/admin/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('style/admin/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('style/admin/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('style/admin/js/main.js') }}"></script>
<script src="{{ asset('style/admin/js/error-pages.js') }}"></script>
</body>
</html>
