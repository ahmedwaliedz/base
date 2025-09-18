<?php
namespace App\Traits\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\InteractsWithMedia;

trait BaseModelTrait {

    use GeneralTrait, BaseFilesTrait, InteractsWithMedia,
    FilterableTrait, HasDynamicRelations,
    HasTranslationsScope,
    Translatable {
        Translatable::getAttribute insteadof BaseFilesTrait;
        BaseFilesTrait::getAttribute as filesGetAttribute;

        Translatable::setAttribute insteadof BaseFilesTrait;
        BaseFilesTrait::setAttribute as filesSetAttribute;
    }

    public function getAttribute($key) {
        if (defined('static::FILES') && in_array($key, static::FILES ?? [], true)) {
            return $this->filesGetAttribute($key);
        }
        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value) {
        if (defined('static::FILES') && in_array($key, static::FILES ?? [], true)) {
            return $this->filesSetAttribute($key, $value);
        }
        return parent::setAttribute($key, $value);
    }

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
