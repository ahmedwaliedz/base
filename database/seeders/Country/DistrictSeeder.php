<?php

namespace Database\Seeders\Country;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use Database\Seeders\Support\SaudiData;
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

        $extraMax = (int) config('seed.counts.'.config('seed.profile').'.districts_extra_per_city_max', 0);
        if ($extraMax <= 0) {
            return;
        }

        $ksaId = Country::query()->where('code', '966')->value('id')
            ?? Country::query()->value('id');

        City::query()->where('country_id', $ksaId)->chunkById(50, function ($cities) use ($extraMax): void {
            foreach ($cities as $city) {
                $enName = $city->translate('en')->name ?? $city->name;
                $count = random_int(3, $extraMax);
                $curated = SaudiData::DISTRICTS[$enName] ?? null;

                for ($i = 0; $i < $count; $i++) {
                    $pick = $curated !== null
                        ? $curated[array_rand($curated)]
                        : SaudiData::DISTRICT_POOL[array_rand(SaudiData::DISTRICT_POOL)];

                    District::create([
                        'city_id' => $city->id,
                        'is_active' => true,
                        'en' => ['name' => $pick['en'].' '.($i + 1)],
                        'ar' => ['name' => $pick['ar'].' '.($i + 1)],
                    ]);
                }
            }
        });
    }
}
