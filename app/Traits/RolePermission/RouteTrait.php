<?php

namespace App\Traits\RolePermission;

use Illuminate\Support\Str;

trait RouteTrait
{
    public static function getAdminRouteNames(): array
    {
        return collect(\Route::getRoutes())
            ->map(function ($route) {
                return $route->getName();
            })
            ->filter(function ($name) {
                return $name && str_starts_with($name, 'admin.');
            })
            ->map(function ($name) {
                return str_replace('admin.', '', $name);
            })
            ->values()
            ->toArray();
    }
}
