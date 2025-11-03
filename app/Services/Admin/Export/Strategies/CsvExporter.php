<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use Illuminate\Support\Str;

class CsvExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $rows = $query->get();
        $columns = $options['columns'] ?? $this->getDefaultColumns($rows);

        // Prepare filename
        $baseName = strtolower(class_basename($options['model'] ?? 'data'));
        $filename = $baseName . '-' . now()->format('Ymd-His') . '.csv';

        // Streamed response to avoid memory spikes on large datasets
        $callback = function () use ($rows, $columns) {
            $output = fopen('php://output', 'w');

            // Add UTF-8 BOM so Excel correctly detects encoding for Arabic/English
            fwrite($output, "\xEF\xBB\xBF");

            // Header row (translated when label is a trans key)
            $headers = [];
            foreach ($columns as $col) {
                if (is_array($col)) {
                    $label = $col['label'] ?? $col['key'] ?? '';
                    $headers[] = is_string($label) ? __(strval($label)) : $label;
                } else {
                    $headers[] = ucfirst(strval($col));
                }
            }
            fputcsv($output, $headers);

            // Data rows
            foreach ($rows as $row) {
                $rowArray = [];
                foreach ($columns as $col) {
                    $key = is_array($col) ? ($col['key'] ?? null) : $col;
                    $value = $this->extractValue($row, $key);
                    // Normalize line breaks and ensure scalar string
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                    $value = str_replace(["\r\n", "\r"], "\n", (string) $value);
                    $rowArray[] = $value;
                }
                fputcsv($output, $rowArray);
            }

            fclose($output);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    protected function getDefaultColumns($rows) {
        if ($rows->isEmpty()) {
            return [];
        }
        $first = (array) $rows->first();
        return collect($first)->keys()->map(fn($key) => ['key' => $key, 'label' => ucfirst($key)])->toArray();
    }

    protected function extractValue($row, $key) {
        if ($key === null) return '';
        // Support Eloquent attribute access and dot notation
        $value = data_get($row, $key);
        return $value;
    }
}


