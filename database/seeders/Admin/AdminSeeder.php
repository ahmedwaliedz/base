<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory()
            ->withSequencedAttributes()
            ->count(100)
            ->create();
    }
}
