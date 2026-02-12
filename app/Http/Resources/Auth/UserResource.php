<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected ?string $token;

    public function __construct($resource, ?string $token = null)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'phone'             => $this->phone,
            'country_code'      => $this->country_code,
            'normalized_phone'  => $this->phone_normalized,
            'name'              => $this->name,
            'token'             => $this->token,
            'email'             => $this->email,
            'is_complete_info'  => $this->is_complete_info,
            'is_active'         => $this->is_active,
            'image'             => $this->image,
        ];
    }
}
