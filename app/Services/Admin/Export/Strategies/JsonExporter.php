<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;

class JsonExporter implements ExporterInterface {
    public function export($query, array $options = []) {
        $rows = $query->get();
        return response()->json($rows);
    }
}
