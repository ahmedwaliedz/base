<?php

namespace App\Traits\RolePermission\Sidebar;


trait BuildUngroupedRoutesTrait
{
    private static function buildUngroupedRoutes(array $names, array $routes): array
    {
        $out = [];
        foreach ($routes as $key => $cfg) {
            if (
                empty($cfg['group'] ?? null)
                && ! empty($cfg['has_child'])
            ) {
                $children = [];
                foreach ($cfg['childs'] as $act => $opts) {
                    $full = "{$key}.{$act}";
                    if (in_array($full, $names) && ! empty($opts['is_sub_route'])) {
                        $children[] = [
                            'title'        => $opts['title'] ?? $act,
                            'icon'         => $opts['icon']  ?? '',
                            'route'        => $full,
                            'children'     => [],
                            'has_dropdown' => false,
                        ];
                    }
                }
                if ($children) {
                    // dropdown
                    $out[$key] = [
                        'title'        => $cfg['title'] ?? $key,
                        'icon'         => $cfg['icon']  ?? '',
                        'route'        => "{$key}.index",
                        'children'     => $children,
                        'has_dropdown' => true,
                    ];
                } elseif (in_array("{$key}.index", $names)) {
                    // simple link to index
                    $out[$key] = [
                        'title'        => $cfg['title'] ?? $key,
                        'icon'         => $cfg['icon']  ?? '',
                        'route'        => "{$key}.index",
                        'children'     => [],
                        'has_dropdown' => false,
                    ];
                }
            }
        }
        return $out;
    }
}
