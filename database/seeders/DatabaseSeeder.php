<?php

namespace Database\Seeders;

use Database\Seeders\Admin\{
    AdminSeeder, PermissionSeeder, RoleSeeder,
};
use Database\Seeders\Country\{
    CitySeeder,CountrySeeder,DistrictSeeder,RegionSeeder
};
use Database\Seeders\MorePage\{
    FaqSeeder,IntroPageSeeder,PageSeeder,SliderSeeder,SocialSeeder
};
use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\Complaint\ComplaintSeeder;
use Database\Seeders\Complaint\ContactMessageSeeder;
use Database\Seeders\User\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
            CountrySeeder::class,
            RegionSeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            CategorySeeder::class,
            PageSeeder::class,
            FaqSeeder::class,
            SliderSeeder::class,
            SocialSeeder::class,
            IntroPageSeeder::class,
            ComplaintSeeder::class,
            ContactMessageSeeder::class,
            UserSeeder::class,
        ]);
    }
}
