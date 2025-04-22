<?php

namespace App\Traits\RolePermission;

use App\Enums\AdminType;
use Illuminate\Support\Str;

trait RouteTrait
{
    public static function getAdminRouteNames(): array
    {
        // 1) collect all admin.* routes
        $all = collect(\Route::getRoutes())
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
}
