<?php

namespace Database\Seeders\Country;

use App\Models\Region;
use File;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('json/regions.json');
        $regions = json_decode(File::get($jsonPath), true);
        foreach ($regions as $regionData) {
            Region::create($regionData);
        }
    }
}
