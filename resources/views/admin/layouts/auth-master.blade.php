<!DOCTYPE html>

<html lang="{{adminLang() == 'ar' ? 'ar' : 'en'}}"
      class="layout-navbar-fixed layout-menu-fixed loaded"
      dir="{{adminDirection()}}"
      data-theme="theme-default"
      data-assets-path="{{asset('style/admin/')}}/"
      data-template="vertical-menu-template"
>
@include('admin.layouts.header-links')
<body>
<div id="page-loader">
    <lottie-player src="{{ asset('storage/uploads/settings/loader.json') }}" background="transparent" speed="0.8" style="width: 300px; height:100%;" loop autoplay></lottie-player>
</div>
@yield('content')
@include('admin.layouts.footer-links')
</body>
</html>
