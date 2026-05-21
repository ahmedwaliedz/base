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
        if (defined(static::class . '::PATH_NAME')) {
            return constant(static::class . '::PATH_NAME');
        }
        $plural = Str::plural(strtolower(class_basename(static::class)));
        $withHyphens = strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $plural));
        $trimmed = ltrim($withHyphens, '-');
        return str_replace('s-', '-', $trimmed);
    }

    public static function smallSingularName(): string {
        if (defined(static::class . '::PATH_NAME')) {
            return Str::singular(constant(static::class . '::PATH_NAME'));
        }
        $singular = strtolower(class_basename(static::class));
        $withHyphens = strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $singular));
        return ltrim($withHyphens, '-');
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
