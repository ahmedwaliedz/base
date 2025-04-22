<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory()
            ->withSequencedType()
            ->withSequencedEmail()
            ->withSequencedRole()
            ->count(5)
            ->create()
        ;
    }
}
