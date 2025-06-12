<?php

namespace App\Services\Admin\Settings;

use App\Enums\SettingTypeEnum;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingService
{
    public function getSettings(): mixed
    {
        return cache()->get('settings');
    }

    public function updateSettings(array $data): void
    {
        collect($data)->each(function ($value, $key) {
            Setting::updateOrCreate(['key' => $key], [ 'type' => static::setTypeAttribute($value) , 'value' => $value ]);
        });

        $this->refreshSettingsCache();
    }

    public static function setTypeAttribute($value): SettingTypeEnum
    {
        // Check if the value is an uploaded file and it's an image
        if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
            if (str_starts_with($value->getMimeType(), 'image/')) {
                return SettingTypeEnum::IMAGE;
            }
            return SettingTypeEnum::FILE;
        }

        return match (gettype($value)) {
            SettingTypeEnum::JSON->value       => SettingTypeEnum::JSON,
            SettingTypeEnum::ARRAY->value      => SettingTypeEnum::JSON,
            SettingTypeEnum::INTEGER->value    => SettingTypeEnum::INTEGER,
            SettingTypeEnum::BOOLEAN ->value   => SettingTypeEnum::BOOLEAN,
            SettingTypeEnum::IMAGE ->value     => SettingTypeEnum::IMAGE,
            SettingTypeEnum::STRING ->value    => SettingTypeEnum::STRING,
            SettingTypeEnum::FILE->value       => SettingTypeEnum::FILE,
            default => $value,
        };
    }

    private function refreshSettingsCache(): void
    {
        cache()->forget('settings');
        Cache::rememberForever('settings', function () {
            return Setting::get()->pluck('value', 'key');
        });
    }
}
