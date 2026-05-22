<?php

namespace App\Http\Requests\Admin\District;

use App\Http\Requests\Admin\BaseAdminRequest;

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
    }

    public function rules(): array
    {
        return [
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'is_active' => ['required', 'boolean'],
            'ar' => ['required', 'array'],
            'ar.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en' => ['required', 'array'],
            'en.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
        ];
    }
}
