<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ExportColumnResolver;
use App\Services\Admin\Export\Support\QueryExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class ExcelExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $columns = ! empty($options['columns'])
            ? $options['columns']
            : $this->getDefaultColumns($query);

        $headings = [];
        foreach ($columns as $col) {
            if (is_array($col)) {
                $label = $col['label'] ?? $col['key'] ?? '';
                $headings[] = is_string($label) ? __(strval($label)) : $label;
            } else {
                $headings[] = ucfirst(strval($col));
            }
        }

        $baseName = strtolower(class_basename($options['model'] ?? 'data'));
        $filename = $baseName . '-' . now()->format('Ymd-His') . '.xlsx';

        $rtl = (bool)($options['rtl'] ?? (app()->getLocale() === 'ar'));

        return Excel::download(new QueryExport($query, $columns, $headings, $rtl), $filename, ExcelFormat::XLSX);
    }

    protected function getDefaultColumns($query) {
        $first = (clone $query)->limit(1)->get()->first();

        if (! $first) {
            return [];
        }

        return ExportColumnResolver::columnsFromSample($first);
    }
}
