<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">

    {{-- Mobile toggle + theme switcher on the start side --}}
    <div class="d-flex align-items-center gap-1">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-1 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>
        <ul class="navbar-nav flex-row align-items-center">
            @include('admin.layouts.parts.style-switch')
        </ul>
    </div>

    {{-- End-side actions --}}
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            @include('admin.layouts.parts.language')
            @include('admin.layouts.parts.notifications')
            @include('admin.layouts.parts.profile-dropdown')
        </ul>
    </div>

</nav>
