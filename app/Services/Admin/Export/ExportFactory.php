<?php
namespace App\Services\Admin\Export;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Strategies\CsvExporter;
use App\Services\Admin\Export\Strategies\JsonExporter;
use App\Services\Admin\Export\Strategies\HtmlExporter;
use App\Services\Admin\Export\Strategies\PdfExporter;
use App\Services\Admin\Export\Strategies\PrintExporter;
use App\Services\Admin\Export\Strategies\WordExporter;
use InvalidArgumentException;

class ExportFactory {
    public static function make(string $format): ExporterInterface {
        return match (strtolower($format)) {
            'csv'   => new CsvExporter(),
            'json'  => new JsonExporter(),
            'html'  => new HtmlExporter(),
            'pdf'   => new PdfExporter(),
            'print' => new PrintExporter(),
            'docx', 'word' => new WordExporter(),
            default => throw new InvalidArgumentException("Unsupported export format: {$format}")
        };
    }
}
