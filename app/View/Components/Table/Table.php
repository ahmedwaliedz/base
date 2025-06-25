<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public ?bool $hasCheckbox;
    public ?bool $hasActions;
    public ?array $headers;
    public  $rows;

    public function __construct(bool $hasCheckbox = false, bool $hasActions = false, array $headers = [],  $rows = null)
    {
        $this->hasCheckbox = $hasCheckbox;
        $this->hasActions = $hasActions;
        $this->headers = $headers;
        $this->rows = $rows ;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.table');
    }
}
