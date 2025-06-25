<?php

namespace App\Traits\Filters;

trait FilterHelpers
{
    protected function shouldApplyFilter($value, $column): bool
    {
        return !empty($value) && $column !== 'order_by';
    }
}
