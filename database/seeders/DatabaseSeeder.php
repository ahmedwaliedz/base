<?php

namespace Database\Seeders;

use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\AdminNotificationSeeder;
use Database\Seeders\Admin\PermissionSeeder;
use Database\Seeders\Admin\RoleSeeder;
use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\Complaint\ComplaintSeeder;
use Database\Seeders\Complaint\ContactMessageSeeder;
use Database\Seeders\Country\CitySeeder;
use Database\Seeders\Country\CountrySeeder;
use Database\Seeders\Country\DistrictSeeder;
use Database\Seeders\Country\RegionSeeder;
use Database\Seeders\MorePage\FaqSeeder;
use Database\Seeders\MorePage\IntroPageSeeder;
use Database\Seeders\MorePage\PageSeeder;
use Database\Seeders\MorePage\PostSeeder;
use Database\Seeders\MorePage\SliderSeeder;
use Database\Seeders\MorePage\SocialSeeder;
use Database\Seeders\Setting\SettingSeeder;
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
            SettingSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            CountrySeeder::class,
            AdminSeeder::class,
            AdminNotificationSeeder::class,
            RegionSeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            CategorySeeder::class,
            PageSeeder::class,
            FaqSeeder::class,
            SliderSeeder::class,
            PostSeeder::class,
            SocialSeeder::class,
            IntroPageSeeder::class,
            UserSeeder::class,
            ComplaintSeeder::class,
            ContactMessageSeeder::class,
        ]);
    }
}
