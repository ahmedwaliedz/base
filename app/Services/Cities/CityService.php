<?php

namespace App\Services\Cities;

use App\Models\City;
use App\Services\HelperQueries\BaseQueryService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CityService
{
    protected BaseQueryService $queryService;

    public function __construct()
    {
        $this->queryService = new BaseQueryService(new City());
    }

    /**
     * Get all cities with pagination
     */
    public function getCities(Request $request): LengthAwarePaginator
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->select(['id', 'region_id', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get limited cities without pagination
     */
    public function getCitiesLimited(Request $request): Collection
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->select(['id', 'region_id', 'is_active'])
            ->orderByIdDesc()
            ->limit($request);
    }

    /**
     * Get cities with their region
     */
    public function getCitiesWithRegion(Request $request): LengthAwarePaginator
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->with(['region'])
            ->select(['id', 'region_id', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get cities by region ID
     */
    public function getCitiesByRegion(Request $request, int $regionId): LengthAwarePaginator
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true, 'region_id' => $regionId])
            ->select(['id', 'region_id', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get limited cities by region ID without pagination
     */
    public function getCitiesByRegionLimited(Request $request, int $regionId): Collection
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true, 'region_id' => $regionId])
            ->select(['id', 'region_id', 'is_active'])
            ->orderByIdDesc()
            ->limit($request);
    }

    /**
     * Get city by ID
     */
    public function getCityById(int $id): ?City
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->find($id);
    }

    /**
     * Get city by ID with region
     */
    public function getCityByIdWithRegion(int $id): ?City
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->with(['region'])
            ->find($id);
    }

    /**
     * Search cities by name
     */
    public function searchCities(Request $request): LengthAwarePaginator
    {
        $searchTerm = $request->get('search');
        
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->select(['id', 'region_id', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get cities count
     */
    public function getCitiesCount(): int
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->count();
    }

    /**
     * Get active cities count
     */
    public function getActiveCitiesCount(): int
    {
        return City::where('is_active', true)->count();
    }

    /**
     * Get inactive cities count
     */
    public function getInactiveCitiesCount(): int
    {
        return City::where('is_active', false)->count();
    }
}
