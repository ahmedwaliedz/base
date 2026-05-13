<?php

namespace App\Builders\Sidebar;

class SimpleRouteBuilder
{
    use DeterminesSidebarRouteActive;

    /**
     * Build a simple route item for the sidebar
     *
     * @param  string  $key  The route key
     * @param  array  $route  The route configuration
     * @return array The sidebar item
     */
    public function build(string $key, array $route): array
    {
        return [
            'is_active' => $this->isRouteActive($key),
            'title' => $route['title'] ?? $key,
            'icon' => $route['icon'] ?? '',
            'route' => $key,
            'children' => [],
        ];
    }
}
