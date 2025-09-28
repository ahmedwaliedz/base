<?php
namespace App\Traits\Models;

use App\Traits\Upload\BaseFilesTrait;
use Astrotomic\Translatable\Translatable;

trait BaseFileWithTranslations {

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
        if (defined('static::FILES') && in_array($key, static::FILES ?? [], true)) {
            return $this->filesGetAttribute($key);
        }

        return $this->translatableGetAttribute($key);
    }

    public function setAttribute($key, $value) {
        if (defined('static::FILES') && in_array($key, static::FILES ?? [], true)) {
            return $this->filesSetAttribute($key, $value);
        }

        return $this->translatableSetAttribute($key, $value);
    }

    /*
     *
     * end ℹ️ℹ️ℹ️ important to use BaseFilesTrait before Translatable trait ℹ️ℹ️ℹ️
     *
    */

}
