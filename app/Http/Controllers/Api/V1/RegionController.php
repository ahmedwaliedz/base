<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use App\Http\Resources\Api\V1\RegionResource;
use App\Http\Resources\Api\V1\RegionCollection;
use App\Traits\Response\SuccessResponseTrait;
use App\Traits\Response\FailResponseTrait;
use App\Traits\Response\PaginationTrait;
use App\Services\HelperQueries\BaseQueryService;

class RegionController extends Controller
{
    use SuccessResponseTrait, FailResponseTrait, PaginationTrait;

    protected BaseQueryService $queryService;

    public function __construct()
    {
        $this->queryService = new BaseQueryService(new Region());
    }

    /**
     * Get all regions
     */
    public function regions(Request $request)
    {
        try {
            $regions = $this->queryService
                ->resetQuery()
                ->whereArray(['is_active' => true])
                ->select(['id', 'country_id', 'is_active'])
                ->orderByIdDesc()
                ->paginate($request);

            return $this->respondWithSuccess('Regions retrieved successfully', 
                (new RegionCollection($regions))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve regions: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get limited regions (no pagination)
     */
    public function regionsLimited(Request $request)
    {
        try {
            $regions = $this->queryService
                ->resetQuery()
                ->whereArray(['is_active' => true])
                ->select(['id', 'country_id', 'is_active'])
                ->orderByIdDesc()
                ->limit($request);

            return $this->respondWithSuccess('Regions retrieved successfully', [
                RegionResource::collection($regions)->toArray($request),
            ]);
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve regions: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get regions with their cities
     */
    public function regionsWithCities(Request $request)
    {
        try {
            $regions = $this->queryService
                ->resetQuery()
                ->whereArray(['is_active' => true])
                ->with(['cities'])
                ->select(['id', 'country_id', 'is_active'])
                ->orderByIdDesc()
                ->paginate($request);

            return $this->respondWithSuccess('Regions retrieved successfully', 
                (new RegionCollection($regions))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve regions: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get regions by country ID
     */
    public function regionsByCountry(Request $request, $countryId)
    {
        try {
            $regions = $this->queryService
                ->resetQuery()
                ->whereArray(['is_active' => true, 'country_id' => $countryId])
                ->select(['id', 'country_id', 'is_active'])
                ->orderByIdDesc()
                ->paginate($request);

            return $this->respondWithSuccess('Regions retrieved successfully', 
                (new RegionCollection($regions))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve regions: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get limited regions by country ID (no pagination)
     */
    public function regionsByCountryLimited(Request $request, $countryId)
    {
        try {
            $regions = $this->queryService
                ->resetQuery()
                ->whereArray(['is_active' => true, 'country_id' => $countryId])
                ->select(['id', 'country_id', 'is_active'])
                ->orderByIdDesc()
                ->limit($request);

            return $this->respondWithSuccess('Regions retrieved successfully', [
                RegionResource::collection($regions)->toArray($request),
            ]);
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve regions: ' . $e->getMessage(), [], 500);
        }
    }
}
