<?php

namespace App\Traits\RolePermission\Sidebar;
trait IndependentRouteTrait
{
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
}
