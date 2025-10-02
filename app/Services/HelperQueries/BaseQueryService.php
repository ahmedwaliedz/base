<?php

namespace App\Services\HelperQueries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Base Query Service
 * 
 * This service provides common query patterns and methods used throughout the application.
 * It includes pagination, filtering, relationship loading, status queries, and translation support.
 */
class BaseQueryService
{
    protected Model $model;
    protected Builder $query;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
    }

    /**
     * Get the base query builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * Reset the query to start fresh
     */
    public function resetQuery(): self
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    // ==================== PAGINATION METHODS ====================

    /**
     * Paginate results with default per_page from request
     */
    public function paginate(Request $request, int $defaultPerPage = 30): LengthAwarePaginator
    {
        $perPage = $request->get('per_page', $defaultPerPage);
        return $this->query->paginate($perPage);
    }

    /**
     * Get limited results without pagination
     */
    public function limit(Request $request, int $defaultLimit = 50): Collection
    {
        $limit = $request->get('limit', $defaultLimit);
        return $this->query->limit($limit)->get();
    }

    /**
     * Get all results
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * Get first result
     */
    public function first(): ?Model
    {
        return $this->query->first();
    }

    /**
     * Get first result or fail
     */
    public function firstOrFail(): Model
    {
        return $this->query->firstOrFail();
    }

    // ==================== FILTERING AND SEARCH METHODS ====================

    /**
     * Apply search filters using the model's search scope
     */
    public function search(Request $request): self
    {
        if ($request->has('filters') && method_exists($this->model, 'scopeSearch')) {
            $this->query = $this->query->search($request->filters);
        }
        return $this;
    }

    /**
     * Apply custom filters
     */
    public function where(array $conditions): self
    {
        $this->query = $this->query->where($conditions);
        return $this;
    }

    /**
     * Apply where condition
     */
    public function whereColumn(string $column, $operator, $value = null): self
    {
        if ($value === null) {
            $this->query = $this->query->where($column, $operator);
        } else {
            $this->query = $this->query->where($column, $operator, $value);
        }
        return $this;
    }

    /**
     * Apply whereIn condition
     */
    public function whereIn(string $column, array $values): self
    {
        $this->query = $this->query->whereIn($column, $values);
        return $this;
    }

    /**
     * Apply whereNotIn condition
     */
    public function whereNotIn(string $column, array $values): self
    {
        $this->query = $this->query->whereNotIn($column, $values);
        return $this;
    }

    /**
     * Apply whereBetween condition
     */
    public function whereBetween(string $column, array $values): self
    {
        $this->query = $this->query->whereBetween($column, $values);
        return $this;
    }

    /**
     * Apply whereNull condition
     */
    public function whereNull(string $column): self
    {
        $this->query = $this->query->whereNull($column);
        return $this;
    }

    /**
     * Apply whereNotNull condition
     */
    public function whereNotNull(string $column): self
    {
        $this->query = $this->query->whereNotNull($column);
        return $this;
    }

    /**
     * Apply whereLike condition for text search
     */
    public function whereLike(string $column, string $value): self
    {
        $this->query = $this->query->where($column, 'like', '%' . $value . '%');
        return $this;
    }

    // ==================== RELATIONSHIP METHODS ====================

    /**
     * Load relationships
     */
    public function with(array $relations): self
    {
        $this->query = $this->query->with($relations);
        return $this;
    }

    /**
     * Load model's default relations if defined
     */
    public function withDefaultRelations(): self
    {
        if (defined($this->model::class . '::RELATIONS')) {
            $this->query = $this->query->with($this->model::RELATIONS);
        }
        return $this;
    }

    /**
     * Load specific relationship
     */
    public function withRelation(string $relation): self
    {
        $this->query = $this->query->with($relation);
        return $this;
    }

    /**
     * Load relationship with constraints
     */
    public function withWhereHas(string $relation, callable $callback): self
    {
        $this->query = $this->query->withWhereHas($relation, $callback);
        return $this;
    }

    /**
     * Load relationship with count
     */
    public function withCount(string $relation): self
    {
        $this->query = $this->query->withCount($relation);
        return $this;
    }

    // ==================== STATUS AND ACTIVE/INACTIVE METHODS ====================

    /**
     * Apply multiple where conditions from array
     */
    public function whereArray(array $conditions): self
    {
        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                // Handle array values (for whereIn, whereBetween, etc.)
                if (count($value) === 2 && isset($value[0]) && isset($value[1])) {
                    // Assume it's a whereBetween condition
                    $this->query = $this->query->whereBetween($column, $value);
                } else {
                    // Assume it's a whereIn condition
                    $this->query = $this->query->whereIn($column, $value);
                }
            } else {
                // Handle single values
                $this->query = $this->query->where($column, $value);
            }
        }
        return $this;
    }

    /**
     * Filter by inactive status
     */
    public function inactive(): self
    {
        $this->query = $this->query->where('is_active', false);
        return $this;
    }

    /**
     * Filter by blocked status
     */
    public function blocked(): self
    {
        $this->query = $this->query->where('is_blocked', true);
        return $this;
    }

    /**
     * Filter by not blocked status
     */
    public function notBlocked(): self
    {
        $this->query = $this->query->where('is_blocked', false);
        return $this;
    }

    /**
     * Filter by status using model's status scope if available
     */
    public function status(string $status): self
    {
        if (method_exists($this->model, 'scopeStatus')) {
            $this->query = $this->query->status($status);
        }
        return $this;
    }

    /**
     * Filter by completed status
     */
    public function completed(): self
    {
        $this->query = $this->query->where('is_completed', true);
        return $this;
    }

    /**
     * Filter by not completed status
     */
    public function notCompleted(): self
    {
        $this->query = $this->query->where('is_completed', false);
        return $this;
    }

    // ==================== SELECTION METHODS ====================

    /**
     * Select specific columns
     */
    public function select(array $columns): self
    {
        $this->query = $this->query->select($columns);
        return $this;
    }

    /**
     * Select specific columns with common fields
     */
    public function selectWithCommon(array $columns): self
    {
        $commonColumns = ['id', 'created_at', 'updated_at'];
        $allColumns = array_merge($commonColumns, $columns);
        $this->query = $this->query->select($allColumns);
        return $this;
    }

    // ==================== ORDERING METHODS ====================

    /**
     * Order by column
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->query = $this->query->orderBy($column, $direction);
        return $this;
    }

    /**
     * Order by latest (created_at desc)
     */
    public function latest(string $column = 'created_at'): self
    {
        $this->query = $this->query->latest($column);
        return $this;
    }

    /**
     * Order by oldest (created_at asc)
     */
    public function oldest(string $column = 'created_at'): self
    {
        $this->query = $this->query->oldest($column);
        return $this;
    }

    /**
     * Order by ID descending
     */
    public function orderByIdDesc(): self
    {
        $this->query = $this->query->orderBy('id', 'desc');
        return $this;
    }

    /**
     * Order by ID ascending
     */
    public function orderByIdAsc(): self
    {
        $this->query = $this->query->orderBy('id', 'asc');
        return $this;
    }

    // ==================== DATE FILTERING METHODS ====================

    /**
     * Filter by date range
     */
    public function dateRange(string $startDate, string $endDate, string $column = 'created_at'): self
    {
        $this->query = $this->query->whereBetween($column, [$startDate, $endDate]);
        return $this;
    }

    /**
     * Filter by start date
     */
    public function fromDate(string $date, string $column = 'created_at'): self
    {
        $this->query = $this->query->whereDate($column, '>=', $date);
        return $this;
    }

    /**
     * Filter by end date
     */
    public function toDate(string $date, string $column = 'created_at'): self
    {
        $this->query = $this->query->whereDate($column, '<=', $date);
        return $this;
    }

    /**
     * Filter by today
     */
    public function today(string $column = 'created_at'): self
    {
        $this->query = $this->query->whereDate($column, today());
        return $this;
    }

    /**
     * Filter by this week
     */
    public function thisWeek(string $column = 'created_at'): self
    {
        $this->query = $this->query->whereBetween($column, [now()->startOfWeek(), now()->endOfWeek()]);
        return $this;
    }

    /**
     * Filter by this month
     */
    public function thisMonth(string $column = 'created_at'): self
    {
        $this->query = $this->query->whereMonth($column, now()->month)
            ->whereYear($column, now()->year);
        return $this;
    }

    // ==================== TRANSLATION METHODS ====================

    /**
     * Filter by translation locale
     */
    public function locale(string $locale): self
    {
        if (method_exists($this->model, 'scopeLocale')) {
            $this->query = $this->query->locale($locale);
        }
        return $this;
    }

    /**
     * Filter by translated field
     */
    public function whereTranslation(string $field, string $value, string $locale = null): self
    {
        if (method_exists($this->model, 'whereTranslation')) {
            $this->query = $this->query->whereTranslation($field, $value, $locale);
        }
        return $this;
    }

    /**
     * Filter by translated field like
     */
    public function whereTranslationLike(string $field, string $value, string $locale = null): self
    {
        if (method_exists($this->model, 'whereTranslationLike')) {
            $this->query = $this->query->whereTranslationLike($field, $value, $locale);
        }
        return $this;
    }

    // ==================== UTILITY METHODS ====================

    /**
     * Find by ID
     */
    public function find(int $id): ?Model
    {
        return $this->query->find($id);
    }

    /**
     * Find by ID or fail
     */
    public function findOrFail(int $id): Model
    {
        return $this->query->findOrFail($id);
    }

    /**
     * Count results
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * Check if exists
     */
    public function exists(): bool
    {
        return $this->query->exists();
    }

    /**
     * Get distinct values
     */
    public function distinct(string $column = null): self
    {
        if ($column) {
            $this->query = $this->query->distinct($column);
        } else {
            $this->query = $this->query->distinct();
        }
        return $this;
    }

    /**
     * Group by column
     */
    public function groupBy(string $column): self
    {
        $this->query = $this->query->groupBy($column);
        return $this;
    }

    /**
     * Having clause
     */
    public function having(string $column, string $operator, $value): self
    {
        $this->query = $this->query->having($column, $operator, $value);
        return $this;
    }

    // ==================== CHAINING EXAMPLES ====================

    /**
     * Example: Get active countries with regions, paginated
     */
    public function getActiveCountriesWithRegions(Request $request): LengthAwarePaginator
    {
        return $this->resetQuery()
            ->whereArray(['is_active' => true])
            ->with(['regions'])
            ->select(['id', 'code', 'flag', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Example: Get limited active users
     */
    public function getActiveUsersLimited(Request $request): Collection
    {
        return $this->resetQuery()
            ->whereArray(['is_blocked' => false, 'is_active' => true])
            ->select(['id', 'name', 'email', 'phone'])
            ->orderByIdDesc()
            ->limit($request);
    }

    /**
     * Example: Search complaints with status filter
     */
    public function searchComplaints(Request $request): LengthAwarePaginator
    {
        return $this->resetQuery()
            ->search($request)
            ->with(['images', 'replays'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Example: Get categories with translations
     */
    public function getCategoriesWithTranslations(Request $request, string $locale = 'ar'): Collection
    {
        return $this->resetQuery()
            ->whereArray(['is_active' => true])
            ->locale($locale)
            ->with(['parent', 'children'])
            ->orderByIdAsc()
            ->get();
    }

    /**
     * Example: Get recent posts with date filter
     */
    public function getRecentPosts(Request $request, int $days = 7): Collection
    {
        $startDate = now()->subDays($days)->toDateString();
        $endDate = now()->toDateString();

        return $this->resetQuery()
            ->whereArray(['is_active' => true])
            ->dateRange($startDate, $endDate)
            ->with(['category', 'user'])
            ->latest()
            ->limit($request);
    }
}
