<?php

namespace App\Traits\Route;

use App\Enums\AdminType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait RouteTrait
{
    public static function getAdminRouteNames(): array
    {
        return match (auth('admin')->user()->type) {
            AdminType::SUPER_ADMIN  => self::getAllRouteListFromFile()->toArray(),
            AdminType::ADMIN        => self::getAdminRouteListByRole(),
            default                 => [],
        };
    }

    public static function getAllRouteListFromFile($file = 'admin')
    {
        $except = self::exceptedRoutesFromRoles();
        return collect(Route::getRoutes($file))
            ->filter(fn($route) => $route->getName() !== null)
            ->filter(fn($route) => Str::startsWith($route->getName(), 'admin.'))
            ->filter(fn($route) => in_array('auth:admin', $route->gatherMiddleware()))
            ->map(fn($route) => $route->getName())
            ->filter(function (string $fullName) use ($except) {
                return !in_array($fullName, $except, true);
            })
            ->values();
    }

    public static function getAdminRouteListByRole(): array
    {
        return array_values(array_intersect(self::getAllRouteListFromFile()->toArray(),  self::getAuthPermissionList()));
    }

    public static function getAuthPermissionList(): array
    {
        return auth('admin')->user()?->role
            ->permissions
            ->pluck('permission')
            ->filter(fn($p) => str_starts_with($p, 'admin.'))
            ->toArray();
    }

    protected static function getRouteParts(string $route = null): array
    {
        $key = Str::after($route, 'admin.');
        return explode('.', $key);
    }

    protected static function getRouteParams(): array
    {
        return Route::current()->parameters();
    }

    protected static function isHome(array $parts): bool
    {
        return isset($parts[0]) && $parts[0] === 'home';
    }

    public static function exceptedRoutesFromRoles(): array
    {
        return ['admin.logout', 'admin.lang.change', 'admin.loginPage', 'admin.login', 'admin.profile', 'admin.roles.getForm'];
    }
}
