<head>
    <meta charset="utf-8" />
    <meta  name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title> {{cache()->get('settings')['name'][adminLang()]}} | @stack('title' , currentRouteNameWithoutAdmin()) </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{asset('style/admin/img/favicon/favicon.ico')}}" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="{{asset('style/admin/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{asset('style/admin/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('style/admin/vendor/fonts/flag-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('style/admin/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('style/admin/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('style/admin/css/demo.css')}}" />
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('style/admin/vendor/libs/typeahead-js/typeahead.css')}}" />
    <script src="{{asset('style/admin/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('style/admin/vendor/js/template-customizer.js')}}"></script>
    <script src="{{asset('style/admin/js/config.js')}}"></script>
    @stack('css')
</head>
