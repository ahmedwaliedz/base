<?php
namespace App\Traits;

trait GeneralEnumTrait {

    /**
     *
     * this is the translation file path
     *
    */

    public const PATH = 'enums';

    /**
     *
     * it returns the label for the enum case
     * you can use it like this: Enum::label('admin')
     * or like this: Enum::CASE->label()
     *
     */
    public function label(?string $value = null): string {
        if ($value !== null) {
            return __(static::PATH . '.' . $value);
        }
        return __(static::PATH . '.' . $this->value);
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
