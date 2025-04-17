<?php

namespace Database\Seeders\Country;

use App\Models\City;
use File;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('json/cities.json');
        $cities = json_decode(File::get($jsonPath), true);

        foreach ($cities as $cityData) {
            City::create($cityData);
        }
    }
}
