<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;

class JsonExporter implements ExporterInterface
{
    public function export($query, array $options = [])
    {
        $model = $query->getModel();

        $columns = collect($model::EXPORT_COLUMNS)->pluck('key')->toArray();

        $rows = $query->get();

        $filtered = $rows->map(function ($item) use ($columns) {
            $data = [];
            foreach ($columns as $col) {
                $data[$col] = $item->{$col};
            }
            return $data;
        });

        $filename = $model->smallPluralName() . '-' . now()->format('Ymd-His') . '.json';

        $json = json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return response($json)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
