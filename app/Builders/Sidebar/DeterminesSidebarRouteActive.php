<?php

namespace App\Builders\Sidebar;

use Illuminate\Support\Str;

trait DeterminesSidebarRouteActive
{
    /**
     * Whether the current request matches this sidebar route or a typical nested resource route.
     */
    private function isRouteActive(string $route): bool
    {
        $name = 'admin.'.$route;

        if (request()->routeIs($name)) {
            return true;
        }

        $parts = explode('.', $route);
        if (count($parts) >= 2 && end($parts) === 'index') {
            array_pop($parts);
            $prefix = implode('.', $parts);

            $current = request()->route()?->getName();
            if (is_string($current) && str_starts_with($current, 'admin.'.$prefix.'.')) {
                $resourceActions = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
                $suffix = Str::after($current, 'admin.'.$prefix.'.');
                $actionSegment = Str::before($suffix, '.') ?: $suffix;

                if (in_array($actionSegment, $resourceActions, true)) {
                    return true;
                }
            }
        }

        return request()->routeIs($name.'.*');
    }
}
