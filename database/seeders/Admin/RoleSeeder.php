<?php

namespace Database\Seeders\Admin;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->count(100)->create();
    }
}
