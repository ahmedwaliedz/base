<?php

namespace App\Traits\Sidebar;

trait MainMethodsTrait
{
    private static function isSubRoute($group, $action)
    {
        return $group['childs'][$action]['is_sub_route'] ?? false;
    }

    private static function isRouteActive($route): bool
    {
        return request()->routeIs('admin.'.$route);
    }
}
