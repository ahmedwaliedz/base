<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mt-3">
        <a href="{{route('admin.home')}}" class="app-brand-link w-100 h-100 d-flex align-items-center justify-content-center">
            <img class="mw-100 h-100" style="object-fit: contain" src="{{cache()->get('settings')['logo']}}" alt="{{cache()->get('settings')['name'][adminLang()]}}">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>

    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {!!   \App\Builders\Sidebar\SidebarBuilder::buildFromConfig() !!}
    </ul>
</aside>
