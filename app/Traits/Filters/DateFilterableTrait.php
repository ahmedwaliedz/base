<?php

namespace App\Traits\Filters;

trait DateFilterableTrait
{
    public function scopeStartDate($query, $date): mixed
    {
        return $this->applyDateFilter($query, $date, '>=');
    }

    public function scopeEndDate($query, $date): mixed
    {
        return $this->applyDateFilter($query, $date, '<=');
    }

    protected function applyDateFilter($query, $date, $operator): mixed
    {
        return $query->whereDate('created_at', $operator, $date);
    }
}
