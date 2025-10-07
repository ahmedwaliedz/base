<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $rows     = $query->get();
        $columns  = $options['columns'] ?? $this->getDefaultColumns($rows);
        $filename = strtolower(class_basename($options['model'] ?? 'data')) . '-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rows, $columns) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // العناوين
            $header = array_map(function ($col) {
                $label = $col['label'] ?? '';
                return is_string($label) ? __($label) : $label;
            }, $columns);
            fputcsv($output, $header);

            // الصفوف
            foreach ($rows as $row) {
                $line = [];
                foreach ($columns as $col) {
                    $val = isset($col['value']) && is_callable($col['value'])
                        ? call_user_func($col['value'], $row)
                        : data_get($row, $col['key'] ?? '');
                    if (is_array($val) || is_object($val)) {
                        $val = json_encode($val, JSON_UNESCAPED_UNICODE);
                    }
                    $line[] = $val;
                }
                fputcsv($output, $line);
            }

            fclose($output);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    protected function getDefaultColumns($rows) {
        if ($rows->isEmpty()) {
            return [];
        }

        $first = (array) $rows->first();
        return collect($first)->keys()->map(fn($key) => ['key' => $key, 'label' => ucfirst($key)])->toArray();
    }
}
