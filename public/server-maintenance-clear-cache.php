<?php

/**
 * Emergency Cache Clear Script
 *
 * WARNING: Delete this file from production after use, or remove the
 *          MAINTENANCE_CLEAR_TOKEN from .env to disable it permanently.
 *
 * Usage:   https://your-domain.com/server-maintenance-clear-cache.php?token=YOUR_SECRET_TOKEN
 *
 * Security:
 * - Reads MAINTENANCE_CLEAR_TOKEN directly from the project root .env file
 *   (does not rely on getenv(), because Laravel's Dotenv is not loaded here)
 * - Falls back to getenv() if the .env file is not readable
 * - Returns 403 if token is missing or does not match
 * - Uses hash_equals() for timing-safe comparison
 * - Only deletes known Laravel bootstrap cache files (no recursive deletes, no arbitrary commands)
 * - Does not accept file names from request input
 * - Does not print the configured token or other .env content
 */

// -- Parse MAINTENANCE_CLEAR_TOKEN from .env ---------------------------------

$configuredToken = '';

$root = dirname(__DIR__);
$envPath = $root . DIRECTORY_SEPARATOR . '.env';

if (is_file($envPath) && is_readable($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines !== false) {
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }
            if (str_starts_with($trimmed, 'MAINTENANCE_CLEAR_TOKEN=')) {
                $value = substr($trimmed, strlen('MAINTENANCE_CLEAR_TOKEN='));
                if (strlen($value) >= 2) {
                    if (($value[0] === '"' && $value[-1] === '"') || ($value[0] === "'" && $value[-1] === "'")) {
                        $value = substr($value, 1, -1);
                    }
                }
                $configuredToken = $value;
                break;
            }
        }
    }
}

// Fallback to environment variable if .env parsing did not yield a token
if ($configuredToken === '') {
    $configuredToken = (string) getenv('MAINTENANCE_CLEAR_TOKEN');
}

// -- Safety checks -----------------------------------------------------------

$forbidden = function (): void {
    http_response_code(403);
    header('Content-Type: text/plain');
    echo "Forbidden.\n";
    exit;
};

if ($configuredToken === '') {
    $forbidden();
}

$providedToken = $_GET['token'] ?? null;

if (! is_string($providedToken)) {
    $forbidden();
}

if ($configuredToken === '' || ! hash_equals($configuredToken, $providedToken)) {
    $forbidden();
}

// -- Files to delete ---------------------------------------------------------

$cacheFiles = [
    __DIR__ . '/../bootstrap/cache/config.php',
    __DIR__ . '/../bootstrap/cache/routes-v7.php',
    __DIR__ . '/../bootstrap/cache/events.php',
    __DIR__ . '/../bootstrap/cache/packages.php',
    __DIR__ . '/../bootstrap/cache/services.php',
];

$results = [];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            $results[] = 'Deleted: ' . basename(dirname($file)) . '/' . basename($file);
        } else {
            $results[] = 'Failed to delete: ' . basename(dirname($file)) . '/' . basename($file);
        }
    } else {
        $results[] = 'Not found: ' . basename(dirname($file)) . '/' . basename($file);
    }
}

// -- Output ------------------------------------------------------------------

header('Content-Type: text/plain');
echo "Cache clear results:\n";
echo implode("\n", $results) . "\n";
echo "\nDone.\n";
