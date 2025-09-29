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

class RegionController extends Controller
{
    use SuccessResponseTrait, FailResponseTrait, PaginationTrait;

    /**
     * Get all regions
     */
    public function regions(Request $request)
    {
        try {
            $regions = Region::select('id', 'country_id', 'is_active')
                ->where('is_active', true)
                ->paginate($request->get('per_page', 30));

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
            $regions = Region::select('id', 'country_id', 'is_active')
                ->where('is_active', true)
                ->limit($request->get('limit', 50))
                ->get();

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
            $regions = Region::with('cities')
                ->select('id', 'country_id', 'is_active')
                ->where('is_active', true)
                ->paginate($request->get('per_page', 30));

            return $this->respondWithSuccess('Regions retrieved successfully', 
                (new RegionCollection($regions))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve regions: ' . $e->getMessage(), [], 500);
        }
    }
}
