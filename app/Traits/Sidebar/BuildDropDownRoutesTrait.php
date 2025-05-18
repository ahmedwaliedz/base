<?php

namespace App\Traits\Sidebar;


trait BuildDropDownRoutesTrait
{
    use BuildSimpleRoutesTrait;
    public static function buildDropDownRoute($route , $key , $names): array
    {
        $out = [];
        $children = [];
        foreach ($route['childes'] as $act => $opts) {
            $full = "{$key}.{$act}";
            if (in_array($full, $names) && ! empty($opts['is_sub_route'])) {
                $children[] = [
                    'is_active'    => self::isRouteActive($full),
                    'title'        => $opts['title'] ?? $act,
                    'icon'         => $opts['icon']  ?? '',
                    'route'        => $full,
                    'children'     => [],
                ];
            }
        }
        if ($children) {
            // dropdown
            $out[$key] = [
                'is_active'    => array_reduce($children, function ($carry, $item) {
                    return $carry || $item['is_active'];
                }, false),
                'title'        => $route['title'] ?? $key,
                'icon'         => $route['icon']  ?? '',
                'route'        => "{$key}.index",
                'children'     => $children,
            ];
        } elseif (in_array("{$key}.index", $names)) {
            $out = self::buildSimpleRoute("{$key}.index" , $route);
        }
        return $out;
    }
}
