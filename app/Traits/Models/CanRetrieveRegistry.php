<?php
namespace App\Traits\Models;

final class CanRetrieveRegistry {
    /**
     * Map of model class => true for models that passed validation.
     * @var array<string,bool>
     */
    private static array $validatedModels = [];

    public static function markValidated(string $modelClass): void {
        self::$validatedModels[$modelClass] = true;
    }

    public static function isValidated(string $modelClass): bool {
        return isset(self::$validatedModels[$modelClass]);
    }

    /**
     * Returns a shallow copy of the registry for logging/inspection.
     * @return array<string,bool>
     */
    public static function all(): array {
        return self::$validatedModels;
    }
}


