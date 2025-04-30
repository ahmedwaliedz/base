<?php

namespace App\Traits\Sidebar;

trait MainMethodsTrait
{
    private static function isSubRoute($group, $action)
    {
        return $group['childs'][$action]['is_sub_route'] ?? false;
    }
}
