<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ExportColumnResolver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $columns  = ! empty($options['columns'])
            ? $options['columns']
            : $this->getDefaultColumns($query);
        $columns  = array_map(function ($col) {
            if (is_array($col)) {
                $label = $col['label'] ?? '';
                $col['label'] = is_string($label) ? __($label) : $label;
            }
            return $col;
        }, $columns);

        $rows     = $query->get();
        $title    = __("admin/main.export") . ' - ' . class_basename($options['model'] ?? 'Model');
        $filename = strtolower(class_basename($options['model'] ?? 'data')) . '-' . now()->format('Ymd-His') . '.pdf';

        $html = View::make('admin.layouts.export.table', compact('title', 'columns', 'rows'))->render();
        $pdf  = Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    protected function getDefaultColumns($query) {
        $first = (clone $query)->limit(1)->get()->first();

        if (! $first) {
            return [];
        }

        return ExportColumnResolver::columnsFromSample($first);
    }
}
