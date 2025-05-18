<?php

namespace App\Traits;

use App\Enums\AdminType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait RouteTrait
{
    public static function getAdminRouteNames(): array
    {
        return match (auth('admin')->user()->type) {
            AdminType::SUPER_ADMIN  => self::getAllRouteListFromFile() ,
            AdminType::ADMIN        => self::getAdminRouteList(),
            default                 => [],
        };
    }

    public static function getAllRouteListFromFile($file = 'admin')
    {
        return collect(\Route::getRoutes($file))
            ->map->getName()
            ->filter(fn($name) => $name && str_starts_with($name, 'admin.'))
            ->map(fn($name) => substr($name, strlen('admin.')))
            ->values()
            ->toArray();
    }

    public static function getAdminRouteList(): array
    {
        $allRoutes = self::getAllRouteListFromFile();
        $perms = auth('admin')->user()?->role
            ->permissions
            ->pluck('permission')
            ->filter(fn($p) => str_starts_with($p, 'admin.'))
            ->map(fn($p) => substr($p, strlen('admin.')))
            ->toArray();

        return array_values(array_intersect($allRoutes, $perms));
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
