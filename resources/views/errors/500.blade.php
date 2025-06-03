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
    <title>{{ __('admin/main.server_error') }} - 500</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/uploads/settings/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/css/pages/page-misc.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/admin/css/error-pages.css') }}" />
    <script src="{{ asset('style/admin/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('style/admin/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('style/admin/js/config.js') }}"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
        html, body {
            overflow: hidden;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .dots-container {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
        }
        .dot {
            position: absolute;
            background-color: rgb(255, 69, 86);
            border-radius: 50%;
            opacity: 0.6;
            animation: move 15s infinite linear;
            z-index: 1;
        }

        /* Tail effect for dots */
        .dot::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: inherit;
            border-radius: 50%;
            opacity: 0.5;
            z-index: 0;
            transform: scale(0);
            transform-origin: center;
            animation: tail-animation 1.5s ease-out;
            animation-iteration-count: 1;
            animation-play-state: paused;
        }

        .dot.moving::after {
            animation-play-state: running;
        }

        @keyframes tail-animation {
            0% {
                transform: scale(0);
                opacity: 0.5;
            }
            100% {
                transform: scale(3);
                opacity: 0;
            }
        }
        /* Create multiple animation variations */
        @keyframes move {
            0% {
                transform: translate(0, 0);
            }
            20% {
                transform: translate(150px, 80px);
            }
            40% {
                transform: translate(80px, 150px);
            }
            60% {
                transform: translate(-80px, 80px);
            }
            80% {
                transform: translate(-150px, -80px);
            }
            100% {
                transform: translate(0, 0);
            }
        }

        /* Add additional animation for more variety */
        .dot:nth-child(2n) {
            animation-name: move-alt;
        }

        @keyframes move-alt {
            0% {
                transform: translate(0, 0);
            }
            25% {
                transform: translate(-120px, 60px);
            }
            50% {
                transform: translate(40px, -100px);
            }
            75% {
                transform: translate(120px, 40px);
            }
            100% {
                transform: translate(0, 0);
            }
        }

        /* Third animation pattern */
        .dot:nth-child(3n) {
            animation-name: move-alt2;
        }

        @keyframes move-alt2 {
            0% {
                transform: translate(0, 0);
            }
            33% {
                transform: translate(100px, -100px);
            }
            66% {
                transform: translate(-100px, -60px);
            }
            100% {
                transform: translate(0, 0);
            }
        }
        .misc-wrapper {
            position: relative;
            z-index: 1;
        }

        /* Custom Back to Home button styling */
        .home-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 28px;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            color: rgb(75, 145, 190);
            background: transparent;
            border: 2px solid rgb(75, 145, 190);
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(75, 145, 190, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .home-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(75, 145, 190, 0.1);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s ease;
            border-radius: 50px;
        }

        .home-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(75, 145, 190, 0.3);
            color: rgb(75, 145, 190);
        }

        .home-btn:hover:before {
            opacity: 1;
        }

        .home-btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 8px rgba(75, 145, 190, 0.4);
        }

        .home-btn i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .home-btn:hover i {
            transform: translateX(-4px);
        }

        /* RTL support for button icon animation */
        html[dir="rtl"] .home-btn:hover i {
            transform: translateX(4px);
        }

        /* Improved styling for h2 and p elements */
        .misc-wrapper h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #566a7f;
            text-align: center;
            margin-bottom: 1rem;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .misc-wrapper p {
            font-size: 1.1rem;
            color: #697a8d;
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Theme-specific styles */
        [data-theme="theme-default"] .misc-wrapper h2 {
            color: #566a7f;
        }

        [data-theme="theme-default"] .misc-wrapper p {
            color: #697a8d;
        }

        /* Dark theme support */
        [data-theme="theme-dark"] .misc-wrapper h2 {
            color: #d3d6e0;
            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
        }

        [data-theme="theme-dark"] .misc-wrapper p {
            color: #b4bdc6;
        }
    </style>
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
                <h2 class="mb-3">{{ __('admin/main.internal_server_error') }}</h2>
                <p class="mb-4">{{ __('admin/main.server_error_occurred') }}</p>
            </div>
            <div class="position-relative">
                <lottie-player src="{{ asset('storage/uploads/settings/ServerError.json') }}" background="transparent" speed="0.8" style="width: 250px; height: 250px; margin: 0 auto; position: relative; z-index: 2;" loop autoplay></lottie-player>
            </div>
            <a href="{{ route('admin.home') }}" class="mt-4 home-btn">
                <i class="ti ti-home-2 me-2"></i>{{ __('admin/main.back_to_home') }}
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
