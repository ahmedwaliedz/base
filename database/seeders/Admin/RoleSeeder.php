<?php

namespace Database\Seeders\Admin;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use HasFactory ;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->count(5)->create();
    }
}
