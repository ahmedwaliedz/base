<?php

namespace Database\Seeders\Country;

use App\Models\Country;
use File;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('json/arab_countries.json');
        $countries = json_decode(File::get($jsonPath), true);

        foreach ($countries as $countryData) {
            Country::create($countryData);
        }
    }
}
