<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryWithCitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name, // Translated name
            'code' => $this->code,
            'flag' => $this->flag,
            'is_active' => $this->is_active,
            'cities' => $this->whenLoaded('regions', function () {
                return $this->regions->flatMap(function ($region) {
                    return $region->cities->map(function ($city) {
                        return [
                            'id' => $city->id,
                            'name' => $city->name,
                            'country_id' => $city->country_id,
                            'region_id' => $city->region_id,
                            'is_active' => $city->is_active,
                            'created_at' => $city->created_at,
                            'updated_at' => $city->updated_at,
                        ];
                    });
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
