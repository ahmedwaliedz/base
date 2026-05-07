<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">

    {{-- Mobile sidebar toggle (hidden on xl+) --}}
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    {{-- Page title + breadcrumb trail (desktop only) --}}
    <div class="navbar-page-section flex-grow-1 d-none d-xl-flex align-items-center">
        {!! \App\Builders\Breadcrumb\BreadcrumbBuilder::buildFromConfig() !!}
    </div>

    {{-- Right-side actions --}}
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            @include('admin.layouts.parts.language')
            @include('admin.layouts.parts.style-switch')
            @include('admin.layouts.parts.notifications')
            @include('admin.layouts.parts.profile-dropdown')
        </ul>
    </div>

</nav>
