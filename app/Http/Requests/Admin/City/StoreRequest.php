<?php

namespace App\Http\Requests\Admin\City;

use App\Http\Requests\Admin\BaseAdminRequest;
use App\Models\Region;

class StoreRequest extends BaseAdminRequest
{
    private const MAX_STRING_LENGTH = 'max:255';

    public function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }

        if ($this->has('region_id')) {
            $region = Region::find($this->input('region_id'));
            if ($region) {
                $this->merge(['country_id' => $region->country_id]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'country_id' => ['sometimes', 'integer', 'exists:countries,id'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'is_active' => ['required', 'boolean'],
            'ar' => ['required', 'array'],
            'ar.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en' => ['required', 'array'],
            'en.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
        ];
    }
}
