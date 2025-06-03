<?php

namespace Database\Seeders\Setting;

use App\Enums\SettingTypeEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::truncate();
        Cache::forget('settings');
        $data = [
            [
                'key'   => 'name',
                'value' => json_encode(['ar' => 'أوامر الشبكه', 'en' => 'Awamer Alshbakah']),
                'type'  => SettingTypeEnum::JSON,
            ],[
                'key'   => 'email',
                'value' => 'aait@info.com',
                'type'  => SettingTypeEnum::STRING,
            ],[
                'key'   => 'whatsapp',
                'value' => '0966555184424',
                'type'  => SettingTypeEnum::STRING,
            ],[
                'key'   => 'phone',
                'value' => '0966555184424',
                'type'  => SettingTypeEnum::STRING,
            ],[
                'key'   => 'logo',
                'value' => 'logo.png',
                'type'  => SettingTypeEnum::IMAGE,
            ],[
                'key'   => 'fav_icon',
                'value' => 'favicon.ico',
                'type'  => SettingTypeEnum::IMAGE,
            ],[
                'key'   => 'no_data_image',
                'value' => 'no_data.gif',
                'type'  => SettingTypeEnum::IMAGE,
            ],[
                'key'   => 'is_production',
                'value' => 0,
                'type'  => SettingTypeEnum::BOOLEAN,

            ],
        ];
        Setting::insert($data);
        Cache::rememberForever('settings', function () {
            return Setting::get()->pluck('value', 'key');
        });
    }
}
