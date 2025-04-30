<?php

namespace App\Traits\Role;
use App\Enums\AdminType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
trait AuthGaurdFirstRouteTrait
{
    public static function firstSidebarRouteName(): ?string
    {
        $sidebar = config('sidebar_routes', []);

        if (empty($sidebar)) {
            return null;
        }

        $firstKey = array_key_first($sidebar);
        $cfg      = $sidebar[$firstKey];

        // no children?
        if (empty($cfg['has_child'])) {
            return "admin.{$firstKey}";
        }

        // explicit `childs` array?
        if (! empty($cfg['childs']) && is_array($cfg['childs'])) {
            $firstChild = array_key_first($cfg['childs']);
            return "admin.{$firstKey}.{$firstChild}";
        }

        // has_child true but no `childs`
        return "admin.{$firstKey}.index";
    }

    /**
     * Check whether a given admin.* route name is declared in
     * your config/sidebar_routes.php (including its children).
     */
    public static function isSidebarRoute(string $fullRouteName): bool
    {
        // strip "admin."
        $key   = Str::after($fullRouteName, 'admin.');
        $parts = explode('.', $key, 2);

        $sidebar = config('sidebar_routes', []);
        if (! isset($sidebar[$parts[0]])) {
            return false;
        }
        $cfg = $sidebar[$parts[0]];

        // no child segment? as long as has_child===false or true, the top route exists
        if (count($parts) === 1) {
            return true;
        }

        // child segment present
        if (! empty($cfg['childs']) && is_array($cfg['childs'])) {
            // declared in the `childs` array?
            return array_key_exists($parts[1], $cfg['childs']);
        }

        // has_child but no explicit list: assume any sub-route is valid
        return ! empty($cfg['has_child']);
    }

    /**
     * Return true if the very first sidebar route (per config)
     * actually exists in that config file.
     */
    public static function firstSidebarRouteExists(): bool
    {
        $first = static::firstSidebarRouteName();
        return $first !== null && static::isSidebarRoute($first);
    }

    /**
     * As before: find the first sidebar route the current admin
     * is permitted to see (or fall back to the first sidebar route).
     */
    public static function firstAdminRoute()
    {
        $user = Auth::guard('admin')->user();

        if (! $user) {
            return route('admin.loginPage');
        }
        if ($user->type == AdminType::SUPER_ADMIN) {
            return route('admin.home');
        }

        $permissions = $user->role
            ->permissions
            ->pluck('permission')
            ->filter(fn($p) => Str::startsWith($p, 'admin.'))
            ->toArray();

        $sidebar = config('sidebar_routes', []);
        foreach ($permissions as $perm) {
            // strip “admin.” → “notifications.sendEmail”
            $tail = Str::after($perm, 'admin.');
            // grab the first segment → “notifications”
            $key = explode('.', $tail, 2);
            // if that key exists in your sidebar config, we’ve got a match
            if (isset($sidebar[$key[0]]) && $key[1] === 'index') {
                return route($perm);
            }
        }
        // FALLBACK TO FIRST SIDEBAR ROUTE
        return route('admin.home');
    }
}
