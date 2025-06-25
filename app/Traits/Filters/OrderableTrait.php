<?php

namespace App\Traits\Filters;

trait OrderableTrait
{
    public function scopeApplyOrder($query, $searchData): mixed
    {
        $orderBy = $searchData['order_by'] ?? 'descending';
        $direction = $this->getOrderDirection($orderBy);
        return $query->orderBy('created_at', $direction);
    }

    protected function getOrderDirection($orderBy): string
    {
        return $orderBy === 'ascending' ? 'asc' : 'desc';
    }
}
