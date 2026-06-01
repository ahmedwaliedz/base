<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $routes = \Route::getRoutes();
        foreach ($routes as $route) {
            $routeName = $route->getName();
            if ($routeName && str_contains($routeName, 'admin.')) {
                \DB::table('permissions')->updateOrInsert(
                    ['permission' => $routeName],
                    ['permission' => $routeName]
                );
            }
        }
    }
}
