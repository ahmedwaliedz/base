<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\Region;
use App\Http\Resources\Api\V1\CountryResource;
use App\Http\Resources\Api\V1\CountryCollection;
use App\Http\Resources\Api\V1\CountryWithCitiesResource;
use App\Http\Resources\Api\V1\CityResource;
use App\Http\Resources\Api\V1\CityWithRegionsResource;
use App\Http\Resources\Api\V1\RegionResource;
use App\Traits\Response\SuccessResponseTrait;
use App\Traits\Response\FailResponseTrait;
use App\Traits\Response\PaginationTrait;

class CountriesController extends Controller
{
    use SuccessResponseTrait, FailResponseTrait, PaginationTrait;

    public function countries(Request $request)
    {
        try {
            $countries = Country::select('id', 'code', 'flag', 'is_active')->where('is_active', true)->paginate($request->get('per_page', 30));

            return $this->respondWithSuccess('Countries retrieved successfully', 
                (new CountryCollection($countries))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve countries: ' . $e->getMessage(), [], 500);
        }
    }

    public function countriesLimited(Request $request)
    {
        try {
            $countries = Country::select('id', 'code', 'flag', 'is_active')
                ->where('is_active', true)
                ->limit($request->get('limit', 50))
                ->get();

            return $this->respondWithSuccess('Countries retrieved successfully', [
                CountryResource::collection($countries)->toArray($request),
            ]);
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve countries: ' . $e->getMessage(), [], 500);
        }
    }

    public function countriesWithRegions(Request $request)
    {
        try {
            $countries = Country::with('regions')->select('id', 'code', 'flag', 'is_active')->where('is_active', true)->paginate($request->get('per_page', 30));

            return $this->respondWithSuccess('Countries retrieved successfully', 
                (new CountryCollection($countries))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve countries: ' . $e->getMessage(), [], 500);
        }
    }

}
