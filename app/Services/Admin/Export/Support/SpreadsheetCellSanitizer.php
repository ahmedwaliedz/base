<?php
namespace App\Services\Admin\Export\Support;

final class SpreadsheetCellSanitizer
{
    /**
     * Prevent CSV/Excel formula injection by prefixing dangerous leading
     * characters with a single quote.
     *
     * Dangerous prefixes: = + - @ tab carriage-return
     */
    public static function sanitize(mixed $value): mixed
    {
        if ($value === null || ! is_string($value) || $value === '') {
            return $value;
        }

        return preg_match('/^[\s\x00-\x1F]*[=+\-@]/', $value) === 1
            ? "'" . $value
            : $value;
    }

    /**
     * Sanitize every value in a row array.
     */
    public static function sanitizeRow(array $row): array
    {
        return array_map([self::class, 'sanitize'], $row);
    }
}
