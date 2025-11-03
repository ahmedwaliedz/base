<?php

namespace App\Services\Countries;

use App\Models\Country;
use App\Services\HelperQueries\BaseQueryService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CountryService
{
    protected BaseQueryService $queryService;

    public function __construct()
    {
        $this->queryService = new BaseQueryService(new Country());
    }

    /**
     * Get all countries with pagination
     */
    public function getCountries(Request $request): LengthAwarePaginator
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->select(['id', 'code', 'flag', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get limited countries without pagination
     */
    public function getCountriesLimited(Request $request): Collection
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->select(['id', 'code', 'flag', 'is_active'])
            ->orderByIdDesc()
            ->limit($request);
    }

    /**
     * Get countries with regions
     */
    public function getCountriesWithRegions(Request $request): LengthAwarePaginator
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->with(['regions'])
            ->select(['id', 'code', 'flag', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get countries with regions and cities
     */
    public function getCountriesWithRegionsAndCities(Request $request): LengthAwarePaginator
    {
        // Use direct query to ensure proper loading
        return Country::where('is_active', true)
            ->with([
                'regions' => function ($query) {
                    $query->where('is_active', true)
                          ->orderBy('id', 'desc');
                },
                'regions.cities' => function ($query) {
                    $query->where('is_active', true)
                          ->orderBy('id', 'desc');
                }
            ])
            ->select(['id', 'code', 'flag', 'is_active'])
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 30));
    }

    /**
     * Get country codes only
     */
    public function getCountryCodes(Request $request): LengthAwarePaginator
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->select(['id', 'code', 'flag'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get country by ID
     */
    public function getCountryById(int $id): ?Country
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->find($id);
    }

    /**
     * Get country by ID with regions
     */
    public function getCountryByIdWithRegions(int $id): ?Country
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->with(['regions'])
            ->find($id);
    }

    /**
     * Get country by ID with regions and cities
     */
    public function getCountryByIdWithRegionsAndCities(int $id): ?Country
    {
        return Country::where('is_active', true)
            ->where('id', $id)
            ->with([
                'regions' => function ($query) {
                    $query->where('is_active', true)
                          ->orderBy('id', 'desc');
                },
                'regions.cities' => function ($query) {
                    $query->where('is_active', true)
                          ->orderBy('id', 'desc');
                }
            ])
            ->first();
    }

    /**
     * Get country by code
     */
    public function getCountryByCode(string $code): ?Country
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true, 'code' => $code])
            ->first();
    }

    /**
     * Search countries by name or code
     */
    public function searchCountries(Request $request): LengthAwarePaginator
    {
        $searchTerm = $request->get('search');
        
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', '%' . $searchTerm . '%')
                      ->orWhere('name', 'like', '%' . $searchTerm . '%');
            })
            ->select(['id', 'code', 'flag', 'is_active'])
            ->orderByIdDesc()
            ->paginate($request);
    }

    /**
     * Get countries count
     */
    public function getCountriesCount(): int
    {
        return $this->queryService
            ->resetQuery()
            ->whereArray(['is_active' => true])
            ->count();
    }

    /**
     * Get active countries count
     */
    public function getActiveCountriesCount(): int
    {
        return Country::where('is_active', true)->count();
    }

    /**
     * Get inactive countries count
     */
    public function getInactiveCountriesCount(): int
    {
        return Country::where('is_active', false)->count();
    }
}
