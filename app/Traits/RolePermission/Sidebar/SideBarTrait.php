<?php

namespace  App\Traits\RolePermission\Sidebar;

use App\Traits\RolePermission\RouteTrait;
use Illuminate\Contracts\View\View;

trait SideBarTrait
{
    use RouteTrait , MainMethodsTrait , BuildSimpleRoutesTrait , BuildUngroupedRoutesTrait , BuildGroupedRoutesTrait;

    public static function getGroupedAdminRoutes(): View
    {
        $routeNames   = self::getAdminRouteNames();
        $routesConfig = config('admin_routes', []);
        $groupsConfig = config('admin_groups', []);

        $menu = [];
        // 1) simple
        $menu += self::buildSimpleRoutes($routeNames, $routesConfig);
        // 2) ungrouped with has_child
        $menu += self::buildUngroupedRoutes($routeNames, $routesConfig);
        // 3) grouped
        $menu += self::buildGroupedRoutes($routeNames, $routesConfig, $groupsConfig);

        return view('admin.layouts.sidebar.links', [
            'routes' => $menu,
        ]);
    }
}
