<?php
namespace App\Traits\Enums;

trait GeneralEnumTrait {

    /**
     *
     * this is the translation file path
     * if you need to change the path, you can define the PATH constant in the enum class
    */

    public const DEFAULT_PATH = 'enums';

    /**
     *
     * it returns the label for the enum case
     * you can use it like this: Enum::label('admin')
     * or like this: Enum::CASE->label()
     *
     */
    public function label(?string $value = null): string {
        $path = defined('static::PATH') ? static::PATH : self::DEFAULT_PATH;
        return __($path . '.' . ($value ?? $this->value));
    }

    /**
     *
     * it returns an array of labels for the enum cases
     */
    public static function labels(): array {
        return array_map(fn($case) => $case->label(), self::cases());
    }

    /**
     *
     * you can use it like this: Enum::forSelect()
     *
    */

    public static function forSelect(): array {
        return array_map(
            fn($case) => ['id' => $case->value, 'name' => $case->label()],
            self::cases()
        );
    }

    /**
     *
     * for Validation we use this method
     *
    */
    public function forValidation(): string {
        return implode(',', array_map(fn($case) => $case->value, self::cases()));
    }
}
