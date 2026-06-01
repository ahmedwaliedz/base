<?php

namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ExportColumnResolver;
use Illuminate\Support\Facades\View;

class PrintExporter implements ExporterInterface
{
    public function export($query, array $options = [])
    {
        $rows = $query->get();
        $columns = ! empty($options['columns'])
            ? $options['columns']
            : $this->getDefaultColumns($query);
        $columns = array_map(function ($col) {
            if (is_array($col)) {
                $label = $col['label'] ?? '';
                $col['label'] = is_string($label) ? __($label) : $label;
            }
            return $col;
        }, $columns);
        $title = __("admin/main.export") . ' - ' . class_basename($options['model'] ?? 'Model');

        return response()->view('admin.layouts.export.table', compact('title', 'columns', 'rows'));
    }

    protected function getDefaultColumns($query)
    {
        $first = (clone $query)->limit(1)->get()->first();

        if (! $first) {
            return [];
        }

        return ExportColumnResolver::columnsFromSample($first);
    }
}
