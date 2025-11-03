<?php
namespace App\Services\Admin\Export\Support;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ArrayExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {
    protected array $rows;
    protected array $headings;
    protected bool $rtl;

    public function __construct(array $rows, array $headings, bool $rtl = false) {
        $this->rows = $rows;
        $this->headings = $headings;
        $this->rtl = $rtl;
    }

    public function array(): array {
        return $this->rows;
    }

    public function headings(): array {
        return $this->headings;
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->rtl) {
                    $event->sheet->getDelegate()->setRightToLeft(true);
                }
            },
        ];
    }
}


