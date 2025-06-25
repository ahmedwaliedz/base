<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class Filter extends Component
{
    /**
     * Create a new component instance.
     *
     * @param array $filters
     * @param bool $hasStartDate
     * @param bool $hasEndDate
     * @param bool $hasOrderBy
     * @param string $mainCol
     * @return void
     */
    public function __construct(
        public array $filters = [],
        public bool $hasStartDate = false,
        public bool $hasEndDate = false,
        public bool $hasOrderBy = false,
        public string $mainCol = 'col-md-2',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table.filter');
    }
}
