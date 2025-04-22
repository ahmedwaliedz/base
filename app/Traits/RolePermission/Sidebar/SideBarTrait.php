<?php

namespace  App\Traits\RolePermission\Sidebar;

use App\Traits\RolePermission\RouteTrait;
use Illuminate\Contracts\View\View;

trait SideBarTrait
{
    use RouteTrait , MainMethodsTrait , IndependentRouteTrait , GroupedRouteTrait;

    public static function getGroupedAdminRoutes(): View
    {
        $routeNames   = self::getAdminRouteNames();
        $routesConfig = config('admin_routes', []);
        $groupsConfig = config('admin_groups', []);
        $menu         = [];

        //
        // 1) SIMPLE ROUTES (no group, no has_child)
        //
        foreach ($routesConfig as $key => $route) {
            if (
                empty($route['group'] ?? null)
                && empty($route['has_child'])
                && in_array($key, $routeNames)
            ) {
                $menu[$key] = [
                    'title'        => $route['title'] ?? $key,
                    'icon'         => $route['icon']  ?? '',
                    'route'        => $key,
                    'children'     => [],
                    'has_dropdown' => false,
                ];
            }
        }

        //
        // 2) UNGROUPED `has_child` ROUTES
        //
        foreach ($routesConfig as $key => $route) {
            if (
                empty($route['group'] ?? null)
                && ! empty($route['has_child'])
            ) {
                $children = [];
                foreach ($route['childs'] as $action => $opts) {
                    $full = "{$key}.{$action}";
                    if (
                        in_array($full, $routeNames)
                        && ! empty($opts['is_sub_route'])
                    ) {
                        $children[] = [
                            'title'        => $opts['title'] ?? $action,
                            'icon'         => $opts['icon']  ?? '',
                            'route'        => $full,
                            'children'     => [],
                            'has_dropdown' => false,
                        ];
                    }
                }

                if (! empty($children)) {
                    // dropdown because sub‑routes exist
                    $menu[$key] = [
                        'title'        => $route['title'] ?? $key,
                        'icon'         => $route['icon']  ?? '',
                        'route'        => "{$key}.index",
                        'children'     => $children,
                        'has_dropdown' => true,
                    ];
                } elseif (in_array("{$key}.index", $routeNames)) {
                    // no sub‑routes flagged → simple link to index
                    $menu[$key] = [
                        'title'        => $route['title'] ?? $key,
                        'icon'         => $route['icon']  ?? '',
                        'route'        => "{$key}.index",
                        'children'     => [],
                        'has_dropdown' => false,
                    ];
                }
            }
        }

        //
        // 3) GROUPED DROPDOWNS (via admin_groups.php)
        //
        foreach ($groupsConfig as $groupKey => $group) {
            if (empty($group['has_child'])) {
                continue;
            }

            $groupItems = [];
            foreach ($routesConfig as $key => $route) {
                if (($route['group'] ?? null) !== $groupKey) {
                    continue;
                }

                // 3.a) direct child (no has_child)
                if (
                    empty($route['has_child'])
                    && in_array($key, $routeNames)
                ) {
                    $groupItems[] = [
                        'title'        => $route['title'] ?? $key,
                        'icon'         => $route['icon']  ?? '',
                        'route'        => $key,
                        'children'     => [],
                        'has_dropdown' => false,
                    ];
                    continue;
                }

                // 3.b) CRUD submenu
                if (! empty($route['has_child'])) {
                    $subs = [];
                    foreach ($route['childs'] as $action => $opts) {
                        $full = "{$key}.{$action}";
                        if (
                            in_array($full, $routeNames)
                            && ! empty($opts['is_sub_route'])
                        ) {
                            $subs[] = [
                                'title'        => $opts['title'] ?? $action,
                                'icon'         => $opts['icon']  ?? '',
                                'route'        => $full,
                                'children'     => [],
                                'has_dropdown' => false,
                            ];
                        }
                    }

                    if (! empty($subs)) {
                        // dropdown since sub‑routes exist
                        $groupItems[] = [
                            'title'        => $route['title'] ?? $key,
                            'icon'         => $route['icon']  ?? '',
                            'route'        => "{$key}.index",
                            'children'     => $subs,
                            'has_dropdown' => true,
                        ];
                    } elseif (in_array("{$key}.index", $routeNames)) {
                        // no sub‑routes flagged → simple link to index
                        $groupItems[] = [
                            'title'        => $route['title'] ?? $key,
                            'icon'         => $route['icon']  ?? '',
                            'route'        => "{$key}.index",
                            'children'     => [],
                            'has_dropdown' => false,
                        ];
                    }
                }
            }

            if (! empty($groupItems)) {
                $menu[$groupKey] = [
                    'title'        => $group['title']  ?? $groupKey,
                    'icon'         => $group['icon']   ?? '',
                    'route'        => null,
                    'children'     => $groupItems,
                    'has_dropdown' => true,
                ];
            }
        }

        return view('admin.layouts.sidebar.links', [
            'routes' => $menu,
        ]);
    }
}
