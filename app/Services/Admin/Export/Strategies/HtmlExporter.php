<?php

namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ExportColumnResolver;
use Illuminate\Support\Facades\View;

class HtmlExporter implements ExporterInterface
{
    public function export($query, array $options = [])
    {
        $rows = $query->get();
        $columns = ! empty($options['columns'])
            ? $options['columns']
            : $this->getDefaultColumns($query);
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
