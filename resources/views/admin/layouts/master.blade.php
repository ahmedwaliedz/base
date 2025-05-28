<!DOCTYPE html>

<html
    lang="{{adminLang() == 'ar' ? 'ar' : 'en'}}" class="layout-navbar-fixed layout-menu-fixed loaded"
    dir="{{adminDirection()}}" data-theme="theme-default" data-assets-path="{{asset('style/admin/')}}/"
    data-template="vertical-menu-template"
>

@include('admin.layouts.header-links')
<body>
<!-- Page Loader -->
<div id="page-loader">
    <lottie-player src="{{ asset('storage/uploads/settings/loader.json') }}" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></lottie-player>
</div>

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        @include('admin.layouts.parts.sidebar.main')
        <div class="layout-page">
            @include('admin.layouts.nav')
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    {!!   \App\Builders\Breadcrumb\BreadcrumbBuilder::buildFromConfig() !!}
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
