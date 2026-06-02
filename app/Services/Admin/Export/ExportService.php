<?php
namespace App\Services\Admin\Export;

use App\Exceptions\ServiceException;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ExportService {
    public function handle(Request $request, $query, array $options = []) {

        $format = strtolower($request->get('export', 'csv'));

        if ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : [$request->ids];
            $query->whereIn('id', $ids);
        }

        try {
            $exporter = ExportFactory::make($format);
        } catch (InvalidArgumentException) {
            return response()->json(['message' => __('response.unsupported_export_format', ['format' => $format])], 400);
        }

        try {
            return $exporter->export($query, $options);
        } catch (ServiceException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }
    }
}
