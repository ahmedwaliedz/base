<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ExportColumnResolver;
use App\Services\Admin\Export\Support\SpreadsheetCellSanitizer;
use Illuminate\Support\Str;

class CsvExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $columns = ! empty($options['columns'])
            ? $options['columns']
            : $this->getDefaultColumns($query);

        $baseName = strtolower(class_basename($options['model'] ?? 'data'));
        $filename = $baseName . '-' . now()->format('Ymd-His') . '.csv';

        $callback = function () use ($query, $columns) {
            $output = fopen('php://output', 'w');

            fwrite($output, "\xEF\xBB\xBF");

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

            foreach ($query->cursor() as $row) {
                $rowArray = [];
                foreach ($columns as $col) {
                    $key = is_array($col) ? ($col['key'] ?? null) : $col;

                    if (isset($col['value']) && is_callable($col['value'])) {
                        $value = call_user_func($col['value'], $row);
                    } else {
                        $value = $this->extractValue($row, $key);
                    }

                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }
                    $value = str_replace(["\r\n", "\r"], "\n", (string) $value);
                    $rowArray[] = SpreadsheetCellSanitizer::sanitize($value);
                }
                fputcsv($output, $rowArray);
            }

            fclose($output);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    protected function getDefaultColumns($query) {
        $first = (clone $query)->limit(1)->get()->first();

        if (! $first) {
            return [];
        }

        return ExportColumnResolver::columnsFromSample($first);
    }

    protected function extractValue($row, $key) {
        if ($key === null) return '';

        return data_get($row, $key);
    }
}
