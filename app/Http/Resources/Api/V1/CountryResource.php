<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the translated name (fallback handled by model)
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'flag' => $this->flag,
            'regions' => $this->whenLoaded('regions', function () {
                return RegionResource::collection($this->regions);
            }),
        ];
    }
}
