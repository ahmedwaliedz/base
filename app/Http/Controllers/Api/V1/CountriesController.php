<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\CountryCollection;
use App\Http\Resources\Api\V1\CountryResource;
use App\Traits\Response\SuccessResponseTrait;
use App\Traits\Response\FailResponseTrait;
use App\Traits\Response\PaginationTrait;
use App\Services\Countries\CountryService;

class CountriesController extends Controller
{
    use SuccessResponseTrait, FailResponseTrait, PaginationTrait;

    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function countries(Request $request)
    {
        try {
            $countries = $this->countryService->getCountries($request);
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
            $countries = $this->countryService->getCountriesLimited($request);

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
            $countries = $this->countryService->getCountriesWithRegions($request);

            return $this->respondWithSuccess('Countries retrieved successfully',
                (new CountryCollection($countries))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve countries: ' . $e->getMessage(), [], 500);
        }
    }

    public function countriesWithRegionsAndCities(Request $request)
    {
        try {
            $countries = $this->countryService->getCountriesWithRegionsAndCities($request);

            return $this->respondWithSuccess('Countries retrieved successfully',
                (new CountryCollection($countries))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve countries: ' . $e->getMessage(), [], 500);
        }
    }

    public function countryCodes(Request $request)
    {
        try {
            $countryCodes = $this->countryService->getCountryCodes($request);

            return $this->respondWithSuccess('Country codes retrieved successfully',
                (new CountryCollection($countryCodes))->toArray($request)
            );
        } catch (\Exception $e) {
            return $this->respondWithFail('Failed to retrieve country codes: ' . $e->getMessage(), [], 500);
        }
    }

}
