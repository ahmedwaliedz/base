<?php

namespace App\Traits\RolePermission\Sidebar;

use App\Traits\RolePermission\RouteTrait;

trait MainMethodsTrait
{
    private static function isSubRoute($group, $action)
    {
        return $group['childs'][$action]['is_sub_route'] ?? false;
    }
}
