<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;

class CopyExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $model = $query->getModel();

        // Determine columns list
        $columns = [];
        if (isset($model::EXPORT_COLUMNS) && is_array($model::EXPORT_COLUMNS)) {
            $columns = collect($model::EXPORT_COLUMNS)->pluck('key')->toArray();
        } else {
            $sample = $query->limit(1)->get();
            if ($sample->isNotEmpty()) {
                $columns = array_keys((array) $sample->first());
            }
        }

        $rows = $query->get();

        $filtered = $rows->map(function ($item) use ($columns) {
            $data = [];
            foreach ($columns as $col) {
                $data[$col] = data_get($item, $col);
            }
            return $data;
        });

        $json = json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Return JSON without attachment headers so the frontend can copy it to clipboard
        return response($json)->header('Content-Type', 'application/json; charset=UTF-8');
    }
}


