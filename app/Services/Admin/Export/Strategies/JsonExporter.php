<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ExportColumnResolver;

class JsonExporter implements ExporterInterface
{
    public function export($query, array $options = [])
    {
        $model = $query->getModel();
        $modelClass = get_class($model);

        if (! empty($options['columns'])) {
            $columns = ExportColumnResolver::normalizeDefinitions($options['columns']);
        } elseif (defined($modelClass . '::EXPORT_COLUMNS') && is_array($modelClass::EXPORT_COLUMNS)) {
            $columns = ExportColumnResolver::normalizeDefinitions($modelClass::EXPORT_COLUMNS);
        } else {
            $columns = $this->getDefaultColumns($query);
        }

        $filename = $model->smallPluralName() . '-' . now()->format('Ymd-His') . '.json';

        $callback = function () use ($query, $columns) {
            $output = fopen('php://output', 'w');
            fwrite($output, "[\n");

            $first = true;
            foreach ($query->cursor() as $item) {
                $data = [];
                foreach ($columns as $col) {
                    $data[$col['key']] = ExportColumnResolver::valueForColumn($item, $col);
                }

                if (! $first) {
                    fwrite($output, ",\n");
                }
                $first = false;

                fwrite($output, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }

            fwrite($output, "\n]\n");
            fclose($output);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'application/json; charset=UTF-8',
        ]);
    }

    protected function getDefaultColumns($query): array
    {
        $first = (clone $query)->limit(1)->get()->first();

        if (! $first) {
            return [];
        }

        return ExportColumnResolver::columnsFromSample($first);
    }
}
