<?php

namespace App\Traits\Sidebar;

trait BuildSimpleRoutesTrait
{
    use MainMethodsTrait;
    public static function buildSimpleRoute($key  , $route): array
    {
        $output = [];
        $output[$key] = [
            'is_active'    => self::isRouteActive($key),
            'title'        => $route['title'] ?? $key,
            'icon'         => $route['icon']  ?? '',
            'route'        => $key,
            'children'     => [],
        ];
        return $output;
    }

}
