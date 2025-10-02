<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\CityResource;
use App\Http\Resources\Api\V1\CityCollection;
use App\Traits\Response\SuccessResponseTrait;
use App\Traits\Response\FailResponseTrait;
use App\Traits\Response\PaginationTrait;
use App\Services\Cities\CityService;

class CityController extends Controller
{
    use SuccessResponseTrait, FailResponseTrait, PaginationTrait;

    protected CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * Get all cities
     */
    public function cities(Request $request)
    {
        try {
            $cities = $this->cityService->getCities($request);

            return $this->respondWithSuccess('Cities retrieved successfully', 
                (new CityCollection($cities))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve cities: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get limited cities (no pagination)
     */
    public function citiesLimited(Request $request)
    {
        try {
            $cities = $this->cityService->getCitiesLimited($request);

            return $this->respondWithSuccess('Cities retrieved successfully', [
                CityResource::collection($cities)->toArray($request),
            ]);
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve cities: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get cities by region ID
     */
    public function citiesByRegion(Request $request, $regionId)
    {
        try {
            $cities = $this->cityService->getCitiesByRegion($request, $regionId);

            return $this->respondWithSuccess('Cities retrieved successfully', 
                (new CityCollection($cities))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve cities: ' . $e->getMessage(), [], 500);
        }
    }

}
