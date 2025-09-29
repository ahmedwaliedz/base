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

    /*
     *
     * end ℹ️ℹ️ℹ️ important to use BaseFilesTrait before Translatable trait ℹ️ℹ️ℹ️
     *
    */

}
