<?php

use App\Traits\RolePermission\RouteTrait;
use Illuminate\Support\Facades\Route;

trait RolesTrait
{
    use RouteTrait;


    //    method to get all admin routes
    public static function getGroupedAdminRoutes(): array
    {
        // get all routes names
        $routeNames = self::getAdminRouteNames();
        // get all routes from config
        $config = config('admin_routes');
        // create array to store the result
        $result = [];
        // loop through the config and check if the route is in the route names
        foreach ($config as $key => $group) {
            if (!empty($group['is_main_route'])) {
                // راوت مستقل مثل home
                $result = array_merge($result, self::processIndependentRoute($key, $group, $routeNames));

                // راوت تجميعي مثل admins
                $result = array_merge($result, self::processGroupedRoute($key, $group, $routeNames));
            }
        }

        return $result;
    }

// method to process independent routes
    private static function processIndependentRoute($key, $group, $routeNames): array
    {
        $result = [];
        if (!isset($group['has_child']) || !$group['has_child']) {
            if (in_array($key, $routeNames)) {
                $result[$key] = [
                    'name' => $key,
                    'icon' => $group['icon'] ?? '',
                    'route' => $key,
                    'children' => [],
                ];
            }
        }

        return $result;
    }

// method to process grouped routes with sub-routes
    private static function processGroupedRoute($key, $group, $routeNames): array
    {
        $result = [];

        if (!empty($group['has_child'])) {
            $children = [];
            $hasDropdown = false;

            foreach ($routeNames as $routeName) {
                if (str_starts_with($routeName, $key . '.')) {
                    $action = str_replace($key . '.', '', $routeName);
                    $icon = $group[$action]['icon'] ?? null;
                    $isSubRoute = self::isSubRoute($group, $action);

                    $children[] = [
                        'name' => $action,
                        'icon' => $icon,
                        'route' => $routeName,
                        'is_sub_route' => $isSubRoute,
                    ];

                    // تعيين hasDropdown إذا كان يوجد is_sub_route
                    if ($isSubRoute) {
                        $hasDropdown = true;
                    }
                }
            }

            if (!empty($children)) {
                $result[$key] = [
                    'name' => $key,
                    'icon' => $group['icon'] ?? '',
                    'route' => $key . '.index',
                    'children' => $children,
                    'has_dropdown' => $hasDropdown,
                ];
            }
        }

        return $result;
    }

// method to check if the route is a sub-route
    private static function isSubRoute($group, $action)
    {
        return $group['childs'][$action]['is_sub_route'] ?? false;
    }




}
