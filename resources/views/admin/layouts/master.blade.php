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
    <lottie-player class="loader-rocket" src="{{ asset('storage/uploads/settings/loader.json') }}" background="transparent" speed="0.8" loop autoplay></lottie-player>
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
<!-- Global Image Preview Modal -->
<div class="modal fade" id="globalImagePreviewModal" tabindex="-1" aria-labelledby="globalImagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <button type="button" class="btn-close ms-auto me-2 mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body text-center p-0">
                <img id="globalImagePreview" src="" alt="" class="img-fluid rounded" style="max-height:80vh;object-fit:contain;">
            </div>
            <div id="globalImageCaption" class="text-center text-muted small py-2 d-none"></div>
        </div>
    </div>
</div>

@include('admin.layouts.footer-links')
</body>
</html>
