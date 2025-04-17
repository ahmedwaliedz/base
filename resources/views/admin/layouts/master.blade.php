
<!DOCTYPE html>

<html lang="{{adminLang() == 'ar' ? 'ar' : 'en'}}" class="layout-navbar-fixed layout-menu-fixed loaded" dir="{{adminDirection()}}" data-theme="theme-default" data-assets-path="{{asset('style/admin/')}}/" data-template="vertical-menu-template">
    @include('admin.layouts.header-links')
    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                @include('admin.layouts.sidebar')
                <div class="layout-page">
                    @include('admin.layouts.nav')
                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('content')
                        </div>
                        @include('admin.layouts.footer')
                    </div>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
            <div class="drag-target"></div>
        </div>
        @include('admin.layouts.footer-links')
    </body>
</html>




