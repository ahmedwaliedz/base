<?php

namespace App\Models;

use App\Enums\SettingTypeEnum;
use App\Traits\Upload\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use UploadTrait;
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public function getValueAttribute($value)
    {
        return match ($this->type) {
            SettingTypeEnum::JSON->value       => json_decode($value, true),
            SettingTypeEnum::ARRAY->value      => (array)   $value,
            SettingTypeEnum::INTEGER->value    => (int)     $value,
            SettingTypeEnum::BOOLEAN ->value   => (bool)    $value,
            SettingTypeEnum::IMAGE ->value     => (string)  $this->handleImagePath($value),
            default                            => $value,
        };
    }
    public function setValueAttribute($value): void
    {
        if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
            $this->attributes['value'] = $this->UploadFile($value) ;
        }

        $this->attributes['value'] = match (gettype($value)) {
            SettingTypeEnum::ARRAY->value      => json_encode($value, true),
            SettingTypeEnum::INTEGER->value    => (int) $value,
            SettingTypeEnum::BOOLEAN->value    => (bool) $value,
            SettingTypeEnum::IMAGE->value      => (string) $this->UploadFile($value),
            default => $value,
        };
    }

    private function handleImagePath(?string $value): ?string
    {
        if (! $value) {
             return Storage::disk('public')->url('uploads/settings/default2.png');
        }
        if (Storage::disk('public')->exists('uploads/settings/' . $value)) {
            return Storage::disk('public')->url('uploads/settings/' . $value);
        }
        return Storage::disk('public')->url('uploads/settings/default.png');
    }
}
