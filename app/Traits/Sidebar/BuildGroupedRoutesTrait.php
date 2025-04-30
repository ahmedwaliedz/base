<?php


namespace App\Traits\Sidebar;


trait BuildGroupedRoutesTrait
{
    private static function buildGroupedRoutes(array $names, array $routes, array $groups): array
    {
        $out = [];
        foreach ($groups as $gKey => $gCfg) {
            if (empty($gCfg['has_child'])) {
                continue;
            }

            $items = [];
            foreach ($routes as $key => $cfg) {
                if (($cfg['group'] ?? null) !== $gKey) {
                    continue;
                }

                // direct child
                if (empty($cfg['has_child']) && in_array($key, $names)) {
                    $items[] = [
                        'title'        => $cfg['title'] ?? $key,
                        'icon'         => $cfg['icon']  ?? '',
                        'route'        => $key,
                        'children'     => [],
                        'has_dropdown' => false,
                    ];
                    continue;
                }

                // CRUD submenu
                if (! empty($cfg['has_child'])) {
                    $subs = [];
                    foreach ($cfg['childs'] as $act => $opts) {
                        $full = "{$key}.{$act}";
                        if (in_array($full, $names) && ! empty($opts['is_sub_route'])) {
                            $subs[] = [
                                'title'        => $opts['title'] ?? $act,
                                'icon'         => $opts['icon']  ?? '',
                                'route'        => $full,
                                'children'     => [],
                                'has_dropdown' => false,
                            ];
                        }
                    }
                    if ($subs) {
                        $items[] = [
                            'title'        => $cfg['title'] ?? $key,
                            'icon'         => $cfg['icon']  ?? '',
                            'route'        => "{$key}.index",
                            'children'     => $subs,
                            'has_dropdown' => true,
                        ];
                    } elseif (in_array("{$key}.index", $names)) {
                        $items[] = [
                            'title'        => $cfg['title'] ?? $key,
                            'icon'         => $cfg['icon']  ?? '',
                            'route'        => "{$key}.index",
                            'children'     => [],
                            'has_dropdown' => false,
                        ];
                    }
                }
            }

            if (! empty($items)) {
                $out[$gKey] = [
                    'title'        => $gCfg['title'] ?? $gKey,
                    'icon'         => $gCfg['icon']  ?? '',
                    'route'        => null,
                    'children'     => $items,
                    'has_dropdown' => true,
                ];
            }
        }
        return $out;
    }
}
