<?php

namespace App\Traits\Filters;

use Illuminate\Support\Facades\Schema;

trait FilterableTrait
{
    use FilterHelpers;
    use OrderableTrait;
    use DateFilterableTrait;

    public function scopeSearch($query, $searchData): mixed
    {
        if (empty($searchData)) {
            return $query->applyOrder($searchData);
        }
        return $query->applyFilters($searchData)->applyOrder($searchData);
    }
    public function scopeApplyFilters($query, $searchData): mixed
    {
        return $query->where(function ($subQuery) use ($searchData) {
            $model = $subQuery->getModel();
            $table = $model->getTable();
            $columns = Schema::getColumnListing($table);

            foreach ($searchData as $column => $value) {
                if ($this->shouldApplyFilter($value, $column)) {
                    $this->applyFilter($subQuery, $column, $value, $model, $columns);
                }
            }
        });
    }

    protected function applyFilter($query, $column, $value, $model, $columns): void
    {
        if (in_array($column, $columns)) {
            $this->applyColumnFilter($query, $column, $value);
        } else {
            $this->applyScopeFilter($query, $column, $value, $model);
        }
    }

    protected function applyColumnFilter($query, $column, $value): void
    {
        $query->where($column, 'like', '%' . $value . '%');
    }

    protected function applyScopeFilter($query, $column, $value, $model): void
    {

        $scopeMethod = 'scope' . \Str::studly($column);
        if (method_exists($model, $scopeMethod)) {
            $query->{\Str::studly($column)}($value);
        }
    }
}
