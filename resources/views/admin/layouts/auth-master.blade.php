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
@yield('content')
@include('admin.layouts.footer-links')
</body>
</html>
