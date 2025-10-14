<?php

namespace App\Traits\Response;

use Illuminate\Pagination\LengthAwarePaginator;

trait PaginationTrait
{
    /**
     * Format pagination data for API response
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    protected function formatPaginationData(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }

    /**
     * Get pagination data with additional metadata
     *
     * @param LengthAwarePaginator $paginator
     * @param array $additionalData
     * @return array
     */
    protected function getPaginationData(LengthAwarePaginator $paginator, array $additionalData = []): array
    {
        $paginationData = $this->formatPaginationData($paginator);
        
        return array_merge($paginationData, $additionalData);
    }
}
