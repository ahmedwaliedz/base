<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityWithRegionsResource extends JsonResource
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
            'country_id' => $this->country_id,
            'region_id' => $this->region_id,
            'is_active' => $this->is_active,
            'country' => $this->whenLoaded('country', function () {
                return [
                    'id' => $this->country->id,
                    'name' => $this->country->name,
                    'code' => $this->country->code,
                    'flag' => $this->country->flag,
                    'is_active' => $this->country->is_active,
                ];
            }),
            'region' => $this->whenLoaded('region', function () {
                return [
                    'id' => $this->region->id,
                    'name' => $this->region->name,
                    'country_id' => $this->region->country_id,
                    'is_active' => $this->region->is_active,
                    'created_at' => $this->region->created_at,
                    'updated_at' => $this->region->updated_at,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
