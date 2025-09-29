<?php
namespace App\Traits\Models;

use Illuminate\Support\Facades\Cache;

trait ModelHasCacheTrait {

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
