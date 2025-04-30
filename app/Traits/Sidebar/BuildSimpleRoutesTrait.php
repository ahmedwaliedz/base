<?php

namespace App\Traits\Sidebar;

trait BuildSimpleRoutesTrait
{
    public static function buildSimpleRoutes(array $names, array $routes): array
    {
        $out = [];
        foreach ($routes as $key => $cfg) {
            if (
                empty($cfg['group'] ?? null)
                && empty($cfg['has_child'])
                && in_array($key, $names)
            ) {
                $out[$key] = [
                    'title'        => $cfg['title'] ?? $key,
                    'icon'         => $cfg['icon']  ?? '',
                    'route'        => $key,
                    'children'     => [],
                    'has_dropdown' => false,
                ];
            }
        }
        return $out;
    }

}
