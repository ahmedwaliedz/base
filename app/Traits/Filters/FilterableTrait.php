<?php
namespace App\Traits\Filters;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait FilterableTrait {
    use FilterHelpers;
    use OrderableTrait;
    use DateFilterableTrait;

    public function scopeSearch($query, $searchData): mixed {
        if (empty($searchData)) {
            return $query->applyOrder($searchData);
        }
        return $query->applyFilters($searchData)->applyOrder($searchData);
    }
    public function scopeApplyFilters($query, $searchData): mixed {
        // Apply special, non-column filters on the root query BEFORE grouping conditions
        if (isset($searchData['get_deleted']) && $this->shouldApplyFilter($searchData['get_deleted'], 'get_deleted')) {
            $this->getDeletedFilter($query, $searchData['get_deleted']);
            unset($searchData['get_deleted']);
        }

        return $query->where(function ($subQuery) use ($searchData) {
            $model   = $subQuery->getModel();
            $table   = $model->getTable();
            $columns = Schema::getColumnListing($table);

            foreach ($searchData as $column => $value) {
                if ($this->shouldApplyFilter($value, $column)) {
                    $this->applyFilter($subQuery, $column, $value, $model, $columns);
                }
            }
        });
    }

    protected function applyFilter($query, $column, $value, $model, $columns): void {
        if (in_array($column, $columns)) {
            $this->applyColumnFilter($query, $column, $value);
        } else if ($column === 'get_deleted') {
            $this->getDeletedFilter($query, $value);
        } else {
            $this->applyScopeFilter($query, $column, $value, $model);
        }
    }

    protected function applyColumnFilter($query, $column, $value): void {
        $query->where($column, 'like', '%' . $value . '%');
    }

    protected function applyScopeFilter($query, $column, $value, $model): void {

        $scopeMethod = 'scope' . Str::studly($column);
        if (method_exists($model, $scopeMethod)) {
            $query->{Str::studly($column)}($value);
        }
    }

    protected function getDeletedFilter($query, $value): void {
        // Accept common truthy checkbox values: true, 1, '1', 'on', 'true'
        if ($value === true || $value === 1 || $value === '1' || $value === 'on' || $value === 'true') {
            // Include soft-deleted rows as well
            $query->onlyTrashed();
        }
    }
}
