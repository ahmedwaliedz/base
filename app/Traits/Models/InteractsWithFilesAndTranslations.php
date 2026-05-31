<?php
namespace App\Traits\Models;

use App\Traits\Upload\BaseFilesTrait;
use Astrotomic\Translatable\Translatable;

trait InteractsWithFilesAndTranslations {

    /*
     *
     * start ℹ️ℹ️ℹ️ important to use BaseFilesTrait before Translatable trait ℹ️ℹ️ℹ️
     *
    */

    use BaseFilesTrait, Translatable {
        Translatable::getAttribute as protected translatableGetAttribute;
        BaseFilesTrait::getAttribute as protected filesGetAttribute;

        Translatable::setAttribute as protected translatableSetAttribute;
        BaseFilesTrait::setAttribute as protected filesSetAttribute;
    }

    public function getAttribute($key) {
        if ($this->isFileAttributeKey($key)) {
            return $this->filesGetAttribute($key);
        }

        return $this->translatableGetAttribute($key);
    }

    public function setAttribute($key, $value) {
        if ($this->isWritableFileAttributeKey($key)) {
            return $this->filesSetAttribute($key, $value);
        }

        return $this->translatableSetAttribute($key, $value);
    }

    /*
     *
     * end ℹ️ℹ️ℹ️ important to use BaseFilesTrait before Translatable trait ℹ️ℹ️ℹ️
     *
     */

    // -----------------------------------------------------------------------
    //  Helpers
    // -----------------------------------------------------------------------

    protected function isFileAttributeKey($key): bool
    {
        if (! defined('static::FILES')) {
            return false;
        }

        $files = static::FILES ?? [];

        if (in_array($key, $files, true)) {
            return true;
        }

        if (is_string($key) && str_ends_with($key, '_url')) {
            $baseKey = substr($key, 0, -4);

            if (! in_array($baseKey, $files, true)) {
                return false;
            }

            $accessor = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))) . 'Attribute';
            if (method_exists($this, $accessor)) {
                return false;
            }

            return true;
        }

        return false;
    }

    protected function isWritableFileAttributeKey($key): bool
    {
        return defined('static::FILES') && in_array($key, static::FILES ?? [], true);
    }
}
