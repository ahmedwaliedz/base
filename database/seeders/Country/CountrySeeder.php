<?php

namespace Database\Seeders\Country;

use File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('json/arab_countries.json');
        $countries = json_decode(File::get($jsonPath), true);

        foreach ($countries as $countryData) {
            $id = DB::table('countries')->insertGetId([
                'code' => $countryData['code'],
                'flag' => $countryData['flag'],
                'is_active' => $countryData['is_active'] ?? true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $translations = [];
            foreach (['en', 'ar'] as $locale) {
                $translations[] = [
                    'country_id' => $id,
                    'locale' => $locale,
                    'name' => $countryData[$locale]['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('country_translations')->insert($translations);
        }
    }
}
