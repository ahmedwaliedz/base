<?php

namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use Illuminate\Support\Facades\View;

class HtmlExporter implements ExporterInterface
{
    public function export($query, array $options = [])
    {
        $rows = $query->get();
        $columns = $options['columns'] ?? $this->getDefaultColumns($rows);
        $title = __("admin/main.export") . ' - ' . class_basename($options['model'] ?? 'Model');

        return response()->view('admin.layouts.export.table', compact('title', 'columns', 'rows'));
    }

    protected function getDefaultColumns($rows)
    {
        if ($rows->isEmpty()) return [];
        $first = (array) $rows->first();
        return collect($first)->keys()->map(fn($key) => ['key' => $key, 'label' => ucfirst($key)])->toArray();
    }
}
