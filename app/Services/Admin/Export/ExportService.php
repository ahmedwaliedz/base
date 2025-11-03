<?php
namespace App\Services\Admin\Export;

use Illuminate\Http\Request;

class ExportService {
    public function handle(Request $request, $query, array $options = []) {

        $format = strtolower($request->get('export', 'csv'));

        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : [$request->ids];
            $query->whereIn('id', $ids);
        }

        $exporter = ExportFactory::make($format);

        return $exporter->export($query, $options);
    }
}
