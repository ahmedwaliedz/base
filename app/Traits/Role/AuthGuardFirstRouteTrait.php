<?php

namespace App\Traits\Role;
use App\Enums\AdminType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
trait AuthGuardFirstRouteTrait
{
    public static function firstAdminRoute()
    {
        return match (auth('admin')->user()->type) {
            AdminType::SUPER_ADMIN  => self::getSuperAdminFirstRoute(),
            AdminType::ADMIN        => self::getNormalAdminFirstRoute(auth('admin')->user()),
            default                 => self::fallbackAdminFirstRoute(),
        };
    }

    public static function fallbackAdminFirstRoute() : string
    {
        return route('admin.home') ;
    }

    private static function getSuperAdminFirstRoute(): string
    {
        return static::fallbackAdminFirstRoute() ;
    }

    private static function getNormalAdminFirstRoute($admin): string
    {
        $permissions = $admin->role->permissions->pluck('permission')->filter(fn($p) => Str::startsWith($p, 'admin.'))->toArray();
        $sidebar = config('sidebar_routes', [])['admin'] ?? [];
        foreach ($permissions as $perm) {
            // strip "admin." → "notifications.sendEmail"
            $tail = Str::after($perm, 'admin.');
            // grab the first segment → "notifications"
            $key = explode('.', $tail, 2);
            // if that key exists in your sidebar config, we've got a match
            if (isset($sidebar[$key[0]]) && (count($key) < 2 || $key[1] === 'index')) {
                return route($perm);
            }
        }
        return static::fallbackAdminFirstRoute() ;
    }
}
