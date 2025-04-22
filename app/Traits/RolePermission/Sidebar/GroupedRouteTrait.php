<?php

namespace App\Traits\RolePermission\Sidebar;


trait GroupedRouteTrait
{
    use  MainMethodsTrait;
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

}
