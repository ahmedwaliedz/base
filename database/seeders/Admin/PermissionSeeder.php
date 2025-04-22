<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $routes = \Route::getRoutes();
        $permissions = [];
        foreach ($routes as $route) {
            if (str_contains($route->getName(), 'admin.')) {
                $permissions[] = [
                    'permission' => $route->getName(),
                ];
            }
        }
        \DB::table('permissions')->insert($permissions);
    }
}
