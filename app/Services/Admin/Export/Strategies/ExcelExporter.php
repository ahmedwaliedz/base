<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class ExcelExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $rows = $query->get();
        $columns = $options['columns'] ?? $this->getDefaultColumns($rows);

        // Build headings (translate if given as keys)
        $headings = [];
        foreach ($columns as $col) {
            if (is_array($col)) {
                $label = $col['label'] ?? $col['key'] ?? '';
                $headings[] = is_string($label) ? __(strval($label)) : $label;
            } else {
                $headings[] = ucfirst(strval($col));
            }
        }

        // Build data matrix
        $data = [];
        foreach ($rows as $row) {
            $line = [];
            foreach ($columns as $col) {
                $key = is_array($col) ? ($col['key'] ?? null) : $col;
                $value = $this->extractValue($row, $key);
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
                $line[] = (string) $value;
            }
            $data[] = $line;
        }

        $baseName = strtolower(class_basename($options['model'] ?? 'data'));
        $filename = $baseName . '-' . now()->format('Ymd-His') . '.xlsx';

        // Enable RTL automatically for Arabic locale, allow override via options
        $rtl = (bool)($options['rtl'] ?? (app()->getLocale() === 'ar'));

        return Excel::download(new ArrayExport($data, $headings, $rtl), $filename, ExcelFormat::XLSX);
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
        return data_get($row, $key);
    }
}


