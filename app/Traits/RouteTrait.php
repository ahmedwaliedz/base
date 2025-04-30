<?php

namespace App\Traits;

use App\Enums\AdminType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait RouteTrait
{
    public static function getAdminRouteNames(): array
    {
        // 1) collect all admin.* routes
        $all = collect(\Route::getRoutes('admin'))
            ->map->getName()
            ->filter(fn($name) => $name && str_starts_with($name, 'admin.'))
            ->map(fn($name) => substr($name, strlen('admin.')))
            ->values()
            ->toArray();

        $user = auth('admin')->user();
        if ($user->type === AdminType::SUPER_ADMIN ) {
            return $all; // Return all routes for super_admin
        }

        if (! $user || ! $user->role) {
            return [];
        }
        // 4) pull this role's permissions (use your real column name here)
        $perms = $user->role
            ->permissions
            ->pluck('permission') // ← change 'permission' to your actual column
            ->filter(fn($p) => str_starts_with($p, 'admin.'))
            ->map(fn($p) => substr($p, strlen('admin.')))
            ->toArray();

        // 5) intersect and reindex
        return array_values(array_intersect($all, $perms));
    }

    protected static function getRouteParts(): array
    {
        $fullName = Route::currentRouteName();
        $key      = Str::after($fullName, 'admin.');
        return explode('.', $key);
    }

    protected static function getRouteParams()
    {
        return Route::current()->parameters();
    }

    protected static function isHome(array $parts): bool
    {
        return isset($parts[0]) && $parts[0] === 'home';
    }
}
