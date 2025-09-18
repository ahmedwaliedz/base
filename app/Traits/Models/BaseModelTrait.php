<?php
namespace App\Traits\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\InteractsWithMedia;

trait BaseModelTrait {

    use GeneralTrait, BaseFilesTrait, InteractsWithMedia, FilterableTrait;

    protected const FILES             = [];
    protected const UPLOAD_DIRECTORY  = 'default';
    protected const UPLOAD_COLLECTION = 'default';
    protected const UPLOAD_TYPE       = 'custom'; // or 'media-library' based on your implementation
    protected const CACHE_KEY         = null;

    protected static function deleteModelCache() {
        if (defined(static::class . "::CACHE_KEY")) {
            Cache::forget(static::CACHE_KEY);
        }
    }

    public static function getCached() {
        return Cache::rememberForever(static::CACHE_KEY, function () {
            return self::latest()->get();
        });
    }

    public static function boot() {
        parent::boot();
        /* creating, created, updating, updated, deleting, deleted, forceDeleted, restored */

        static::created(function ($model) {
            self::deleteModelCache();
        });
        static::updated(function ($model) {
            self::deleteModelCache();
        });
        static::deleted(function ($model) {
            self::deleteModelCache();
            self::deleteFiles($model);
        });
    }


}
