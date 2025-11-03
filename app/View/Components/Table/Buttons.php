<?php
namespace App\View\Components\Table;

use Illuminate\View\Component;

class Buttons extends Component {
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $createRoute = '',
        public bool $hasNotification = false,
        public bool $hasEmail = false,
        public bool $hasDeleteAll = false,
        public string | bool $deleteAllRoute = false,
        public bool $hasReload = false,
        public bool $hasFilter = false,
        public bool $hasExtraButtons = false,
        public bool $hasExport = false,
        public bool $exportPrint = false,
        public bool $exportPdf = false,
        public bool $exportExcel = false,
        public bool $exportWord = false,
        public bool $exportJson = false,
        public bool $exportCopy = false,
        public bool $hasPagination = false,
        public int $perPage = 30
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.table.buttons');
    }
}
