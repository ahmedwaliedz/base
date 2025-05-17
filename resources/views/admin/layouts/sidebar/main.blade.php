<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{route('admin.home')}}" class="app-brand-link w-100">
            <img class="w-100 " style="object-fit: contain" height="50px" src="{{cache()->get('settings')['logo']}}" alt="{{cache()->get('settings')['name'][adminLang()]}}">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>

    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {!!   \App\Traits\Sidebar\SideBarTrait::getGroupedAdminRoutes() !!}
    </ul>
</aside>
