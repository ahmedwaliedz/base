<?php

namespace Database\Seeders\Setting;

use App\Enums\SettingTypeEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/**
 * Seeds default application settings exposed to the public site and admin (branding, contact, location, pricing keys, SMTP).
 */
class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::truncate();
        Cache::forget('settings');
        $mainData = [
            [
                'key' => 'name',
                'value' => json_encode(['ar' => 'SGA', 'en' => 'SGA']),
                'type' => SettingTypeEnum::JSON,
            ], [
                'key' => 'email',
                'value' => 'SGA@gmail.com',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'whatsapp',
                'value' => '0966555184424',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'phone',
                'value' => '0966555184424',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'is_production',
                'value' => 0,
                'type' => SettingTypeEnum::BOOLEAN,
            ],
        ];
        $pricingData = [
            [
                'key' => 'app_commission',
                'value' => 15, // 15% commission
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'vat_ratio',
                'value' => 15, // or 'fixed'
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'coupon_max_ratio',
                'value' => 80,
                'type' => SettingTypeEnum::STRING,
            ],
        ];
        $images = [
            [
                'key' => 'logo',
                'value' => 'logo.png',
                'type' => SettingTypeEnum::IMAGE,
            ], [
                'key' => 'fav_icon',
                'value' => 'favicon.ico',
                'type' => SettingTypeEnum::IMAGE,
            ], [
                'key' => 'no_data_image',
                'value' => 'no_data.gif',
                'type' => SettingTypeEnum::IMAGE,
            ],
        ];
        $locationData = [
            [
                'key' => 'map_desc',
                'value' => json_encode(['ar' => 'الرياض - السعودية', 'en' => 'Riyadh - Saudi Arabia']),
                'type' => SettingTypeEnum::JSON,
            ], [
                'key' => 'lat',
                'value' => '24.7136',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'lng',
                'value' => '46.6753',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'google_map_api_key',
                'value' => 'AIzaSyA9S2ndLXNQAUC10JXWv9ajJljAxcVF-eM',
                'type' => SettingTypeEnum::STRING,
            ],
        ];
        $smtpData = [
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'mail_port',
                'value' => '2525',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'mail_username',
                'value' => '',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'mail_password',
                'value' => '',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'mail_from_address',
                'value' => 'info@example.com',
                'type' => SettingTypeEnum::STRING,
            ], [
                'key' => 'mail_from_name',
                'value' => 'Example',
                'type' => SettingTypeEnum::STRING,
            ],
        ];
        Setting::insert(array_merge($mainData, $pricingData, $images, $locationData, $smtpData));
        Cache::rememberForever('settings', function () {
            return Setting::get()->pluck('value', 'key');
        });
    }
}
