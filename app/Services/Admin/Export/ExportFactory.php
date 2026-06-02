<?php
namespace App\Services\Admin\Export;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Strategies\HtmlExporter;
use App\Services\Admin\Export\Strategies\JsonExporter;
use App\Services\Admin\Export\Strategies\CopyExporter;
use App\Services\Admin\Export\Strategies\CsvExporter;
use App\Services\Admin\Export\Strategies\PdfExporter;
use App\Services\Admin\Export\Strategies\PrintExporter;
use App\Services\Admin\Export\Strategies\ExcelExporter;
use InvalidArgumentException;

class ExportFactory {
    public static function make(string $format): ExporterInterface {
        return match (strtolower($format)) {
            'csv'   => new CsvExporter(),
            'xlsx', 'xls', 'excel' => new ExcelExporter(),
            'json'  => new JsonExporter(),
            'copy'  => new CopyExporter(),
            'html'  => new HtmlExporter(),
            'pdf'   => new PdfExporter(),
            'print' => new PrintExporter(),
            default => throw new InvalidArgumentException("Unsupported export format: {$format}")
        };
    }
}
