<?php

namespace App\Builders\Sidebar;

use App\Builders\BaseBuilder;
use Illuminate\Contracts\View\View;

class SidebarBuilder extends BaseBuilder
{
    /**
     * Check if the current route matches the given route
     *
     * @param string $route The route to check
     * @return bool Whether the route is active
     */
    private static function isRouteActive(string $route): bool
    {
        return request()->routeIs('admin.'.$route);
    }

    /**
     * Build sidebar routes based on configuration
     *
     * @return View Rendered sidebar routes view
     */
    public static function buildFromConfig(): View
    {
        $routeNames = self::getAdminRouteNames();
        $routesConfig = config('sidebar_routes', [])['admin'];
        $groupsConfig = config('sidebar_groups', [])['admin'];

        // Create a new sidebar builder
        $sidebarBuilder = new self();

        // Process each route in the configuration
        foreach ($routesConfig as $key => $route) {
            if (empty($route['group'] ?? null) && empty($route['has_child']) && in_array('admin.'.$key, $routeNames)) {
                $sidebarBuilder->addSimpleRoute($key, $route);
            } elseif (empty($route['group'] ?? null) && !empty($route['has_child'])) {
                $sidebarBuilder->addDropDownRoute($route, $key, $routeNames);
            } elseif (!empty($route['group'] ?? null)) {
                $sidebarBuilder->addGroupRoute($route, $key, $routeNames, $groupsConfig);
            }
        }

        return $sidebarBuilder->render();
    }

    /**
     * Add a simple route to the sidebar
     *
     * @param string $key The route key
     * @param array $route The route configuration
     * @return $this
     */
    public function addSimpleRoute(string $key, array $route): self
    {
        return $this->addItem([
            'is_active' => self::isRouteActive($key),
            'title'     => $route['title'] ?? $key,
            'icon'      => $route['icon'] ?? '',
            'route'     => $key,
            'children'  => [],
        ], $key);
    }

    /**
     * Add a dropdown route to the sidebar
     *
     * @param array $route The route configuration
     * @param string $key The route key
     * @param array $names Available route names
     * @return $this
     */
    public function addDropDownRoute(array $route, string $key, array $names): self
    {
        $children = [];
        foreach ($route['childes'] as $act => $opts) {
            $full = "{$key}.{$act}";
            if (in_array('admin.'.$full, $names) && !empty($opts['is_sub_route'])) {
                $children[] = [
                    'is_active' => self::isRouteActive($full),
                    'title'     => $opts['title'] ?? $act,
                    'icon'      => $opts['icon'] ?? '',
                    'route'     => $full,
                    'children'  => [],
                ];
            }
        }

        if ($children) {
            // dropdown
            $this->addItem([
                'is_active' => array_reduce($children, function ($carry, $item) {
                    return $carry || $item['is_active'];
                }, false),
                'title'     => $route['title'] ?? $key,
                'icon'      => $route['icon'] ?? '',
                'route'     => "{$key}.index",
                'children'  => $children,
            ], $key);
        } elseif (in_array("admin.{$key}.index", $names)) {
            $this->addSimpleRoute("{$key}.index", $route);
        }

        return $this;
    }

    /**
     * Add a group route to the sidebar
     *
     * @param array $route The route configuration
     * @param string $key The route key
     * @param array $names Available route names
     * @param array $groups Group configurations
     * @return $this
     */
    public function addGroupRoute(array $route, string $key, array $names, array $groups): self
    {
        $group = $groups[$route['group']];
        $child = [];

        if (empty($route['has_child']) && in_array($key, $names)) {
            // Create a temporary builder to get the simple route
            $tempBuilder = new self();
            $tempBuilder->addSimpleRoute($key, $route);
            $child[] = $tempBuilder->getItems()[$key];
        } elseif (!empty($route['has_child'])) {
            // Create a temporary builder to get the dropdown route
            $tempBuilder = new self();
            $tempBuilder->addDropDownRoute($route, $key, $names);
            $tempRoutes = $tempBuilder->getItems();
            if (!empty($tempRoutes)) {
                $child[] = reset($tempRoutes);
            }
        }

        // Get existing children for this group if any
        $items = $this->getItems();
        $existingChildren = $items[$route['group']]['children'] ?? [];
        $children = array_merge($existingChildren, $child);

        if (!empty($children)) {
            $this->addItem([
                'is_active' => array_reduce($children, function ($carry, $item) {
                    return $carry || $item['is_active'];
                }, false),
                'title'     => $group['title'] ?? $route['group'],
                'icon'      => $group['icon'] ?? '',
                'route'     => null,
                'children'  => $children,
            ], $route['group']);
        }

        return $this;
    }

    /**
     * Get the built sidebar routes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->getItems();
    }

    /**
     * Render the sidebar routes view
     *
     * @return View
     */
    public function render(): View
    {
        return view('admin.layouts.parts.sidebar.routes-container', [
            'routes' => $this->getItems(),
        ]);
    }
}
