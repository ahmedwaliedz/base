<?php
namespace App\Services\Admin\Export\Support;

use Illuminate\Database\Eloquent\Model;

final class ExportColumnResolver
{
    /**
     * Extract clean attribute keys from a sampled row.
     *
     * - Eloquent Model → getAttributes() keys (no internal storage keys)
     * - array → array_keys()
     * - stdClass / other → array_keys((array))
     * - null → empty array
     */
    public static function keysFromSample(mixed $sample): array
    {
        if ($sample === null) {
            return [];
        }

        if ($sample instanceof Model) {
            return array_keys($sample->getAttributes());
        }

        if (is_array($sample)) {
            return array_keys($sample);
        }

        return array_keys((array) $sample);
    }

    /**
     * Build column definitions from a sampled row.
     *
     * Returns [['key' => string, 'label' => string], ...]
     * suitable for the exporters' column format.
     */
    public static function columnsFromSample(mixed $sample): array
    {
        return array_map(
            fn (string $key) => [
                'key'   => $key,
                'label' => ucfirst(str_replace('_', ' ', $key)),
            ],
            self::keysFromSample($sample)
        );
    }

    /**
     * Extract a single column key from a flexible column definition.
     *
     * Handles:
     * - string        → the string itself
     * - ['key' => K]  → K
     * - null / empty  → null (filtered out by keysFromDefinitions)
     */
    public static function keyFromDefinition(mixed $column): ?string
    {
        if (is_string($column) && $column !== '') {
            return $column;
        }

        if (is_array($column) && isset($column['key']) && is_string($column['key']) && $column['key'] !== '') {
            return $column['key'];
        }

        return null;
    }

    /**
     * Normalize a columns array into a flat list of string keys.
     *
     * Accepts the same mixed column definitions as all exporters:
     * strings, ['key' => ..., 'label' => ..., 'value' => ...], etc.
     */
    public static function keysFromDefinitions(array $columns): array
    {
        return array_values(array_filter(
            array_map([self::class, 'keyFromDefinition'], $columns),
            fn ($key) => $key !== null
        ));
    }

    /**
     * Normalize a mixed columns array into a unified definition format.
     *
     * Each entry becomes: ['key' => string, ...original extras...]
     * Strings are promoted to ['key' => string].
     * Invalid entries are filtered out.
     */
    public static function normalizeDefinitions(array $columns): array
    {
        $normalized = [];

        foreach ($columns as $column) {
            if (is_string($column) && $column !== '') {
                $normalized[] = ['key' => $column];
                continue;
            }

            if (
                is_array($column)
                && isset($column['key'])
                && is_string($column['key'])
                && $column['key'] !== ''
            ) {
                $normalized[] = $column;
            }
        }

        return $normalized;
    }

    /**
     * Extract a value from a row for a given column definition.
     *
     * Uses the column's `value` callable when present, otherwise
     * falls back to data_get() with the column key.
     */
    public static function valueForColumn(mixed $row, array $column): mixed
    {
        if (isset($column['value']) && is_callable($column['value'])) {
            return ($column['value'])($row);
        }

        return data_get($row, $column['key']);
    }
}
