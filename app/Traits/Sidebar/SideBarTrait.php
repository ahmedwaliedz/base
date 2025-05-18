<?php

namespace  App\Traits\Sidebar;

use App\Traits\RouteTrait;
use Illuminate\Contracts\View\View;

trait SideBarTrait
{
    use RouteTrait  , BuildSimpleRoutesTrait , BuildDropDownRoutesTrait , BuildGroupRoutesTrait;

    public static function getSideBarRoutes(): View
    {
        $routeNames = self::getAdminRouteNames();
        $routesConfig = config('sidebar_routes', []);
        $groupsConfig = config('sidebar_groups', []);

        $routesList = [] ;
        foreach ($routesConfig as $key => $route) {
            if (empty($route['group'] ?? null) && empty($route['has_child'])  && in_array($key, $routeNames) ) {
                $routesList = array_merge($routesList, self::buildSimpleRoute($key , $route));
            }elseif (empty($route['group'] ?? null) && ! empty($route['has_child'])) {
                $routesList = array_merge($routesList, self::buildDropDownRoute($route, $key, $routeNames));
            }elseif(!empty($route['group'] ?? null)){
                $routesList = array_merge($routesList, self::buildGroupRoute($route, $key, $routeNames , $groupsConfig, $routesList));
            }
        }
        return view('admin.layouts.sidebar.routes-container', [
            'routes' => $routesList,
        ]);
    }

}
