<?php

namespace App\Builders\Sidebar;

class SimpleRouteBuilder
{
    /**
     * Build a simple route item for the sidebar
     *
     * @param string $key The route key
     * @param array $route The route configuration
     * @return array The sidebar item
     */
    public function build(string $key, array $route): array
    {
        return [
            'is_active' => $this->isRouteActive($key),
            'title'     => $route['title'] ?? $key,
            'icon'      => $route['icon'] ?? '',
            'route'     => $key,
            'children'  => [],
        ];
    }

    /**
     * Check if the current route matches the given route
     *
     * @param string $route The route to check
     * @return bool Whether the route is active
     */
    private function isRouteActive(string $route): bool
    {
        return request()->routeIs('admin.'.$route);
    }
}
