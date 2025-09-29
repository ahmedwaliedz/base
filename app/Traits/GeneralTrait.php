<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait GeneralTrait {
    public function languages(): array {
        return ['ar', 'en'];
    }

    public function generateCode($length = 5): string {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= random_int(0, 9);
        }
        return $code;
    }

    public function generateRandomString() {
        $length = 16;
        $chars  = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str    = "";

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

    public function scopeForSelect($query, array $fields) {
        return $query->get()->map(function ($item) use ($fields) {
            $output = [];

            foreach ($fields as $field) {
                [$originalField, $alias] = array_pad(
                    preg_split('/\s+as\s+/i', $field),
                    2,
                    null
                );

                $alias = $alias ?? $originalField;

                if (
                    in_array($originalField, $item->translatedAttributes)
                ) {
                    $output[$alias] = $item->translateOrDefault(app()->getLocale())?->$originalField;
                } else {
                    $output[$alias] = $item->{$originalField};
                }
            }

            return $output;
        });
    }

}
