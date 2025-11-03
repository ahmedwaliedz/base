<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $rows     = $query->get();
        $columns  = $options['columns'] ?? $this->getDefaultColumns($rows);
        // Translate labels if they are translation keys
        $columns  = array_map(function ($col) {
            if (is_array($col)) {
                $label = $col['label'] ?? '';
                $col['label'] = is_string($label) ? __($label) : $label;
            }
            return $col;
        }, $columns);
        $title    = __("admin/main.export") . ' - ' . class_basename($options['model'] ?? 'Model');
        $filename = strtolower(class_basename($options['model'] ?? 'data')) . '-' . now()->format('Ymd-His') . '.pdf';

        $html = View::make('admin.layouts.export.table', compact('title', 'columns', 'rows'))->render();
        $pdf  = Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    protected function getDefaultColumns($rows) {
        if ($rows->isEmpty()) {
            return [];
        }

        $first = (array) $rows->first();
        return collect($first)->keys()->map(fn($key) => ['key' => $key, 'label' => ucfirst($key)])->toArray();
    }
}
