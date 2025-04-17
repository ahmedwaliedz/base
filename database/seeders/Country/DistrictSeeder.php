<?php

namespace Database\Seeders\Country;

use App\Models\District;
use File;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('json/districts.json');
        $districts = json_decode(File::get($jsonPath), true);
        foreach ($districts as $districtData) {
            District::create($districtData);
        }
    }
}
