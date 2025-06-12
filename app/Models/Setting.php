<?php

namespace App\Models;

use App\Enums\SettingTypeEnum;
use App\Traits\Upload\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;
    use UploadTrait;

    protected const UPLOAD_DIRECTORY = 'settings';
    protected const UPLOAD_COLLECTION = 'settings';
    protected const UPLOAD_TYPE = 'media-library'; // or 'media-library' based on your implementation

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
            SettingTypeEnum::IMAGE ->value     => (string)  $this->handleImagePath(file: $value,  directory: self::UPLOAD_DIRECTORY,  collection: self::UPLOAD_COLLECTION , uploadMethod: self::UPLOAD_TYPE),
            default                            => $value,
        };
    }

    public function setValueAttribute($value): void
    {
        if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
            $this->attributes['value'] = $this->upload(
                file : $value,
                directory:  self::UPLOAD_DIRECTORY,
                collection:  self::UPLOAD_COLLECTION,
                uploadMethod: self::UPLOAD_TYPE
            );
            return;
        }

        $this->attributes['value'] = match (gettype($value)) {
            SettingTypeEnum::ARRAY->value      => json_encode($value, true),
            SettingTypeEnum::INTEGER->value    => (int) $value,
            SettingTypeEnum::BOOLEAN->value    => (bool) $value,
            SettingTypeEnum::IMAGE->value      => (string) $value,
            default => $value,
        };
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->registerImageConversions($media);
    }
}
