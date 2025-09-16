<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneralTrait
{
    public function languages() : array
    {
        return ['ar', 'en'];
    }

    public function generateCode($length = 5): string
    {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= random_int(0, 9);
        }
        return $code;
    }

    public function generateRandomString() {
        $length = 16;
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }

    
    public static function smallPluralName(): string {
        return Str::plural(strtolower(class_basename(static::class)));
    }

    public static function smallSingularName(): string {
        return strtolower(class_basename(static::class));
    }

}
