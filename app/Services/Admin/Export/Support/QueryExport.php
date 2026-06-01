<?php
namespace App\Services\Admin\Export\Support;

use App\Services\Admin\Export\Support\SpreadsheetCellSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class QueryExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithChunkReading, WithEvents
{
    protected Builder $query;
    protected array $columns;
    protected array $headings;
    protected bool $rtl;

    public function __construct(Builder $query, array $columns, array $headings, bool $rtl = false)
    {
        $this->query    = $query;
        $this->columns  = $columns;
        $this->headings = $headings;
        $this->rtl      = $rtl;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * Map a single row to an array of values.
     * Each column entry supports {key, value, label}.
     */
    public function map($row): array
    {
        $line = [];

        foreach ($this->columns as $col) {
            $key = is_array($col) ? ($col['key'] ?? null) : $col;

            if (isset($col['value']) && is_callable($col['value'])) {
                $value = call_user_func($col['value'], $row);
            } else {
                $value = $this->extractValue($row, $key);
            }

            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $line[] = SpreadsheetCellSanitizer::sanitize((string) $value);
        }

        return $line;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->rtl) {
                    $event->sheet->getDelegate()->setRightToLeft(true);
                }
            },
        ];
    }

    protected function extractValue($row, $key)
    {
        if ($key === null) return '';

        return data_get($row, $key);
    }
}
