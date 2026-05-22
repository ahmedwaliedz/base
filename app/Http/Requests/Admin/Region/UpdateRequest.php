<?php

namespace App\Http\Requests\Admin\Region;

use App\Http\Requests\Admin\BaseAdminRequest;

class UpdateRequest extends BaseAdminRequest
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
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'is_active' => ['required', 'boolean'],
            'ar' => ['required', 'array'],
            'ar.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en' => ['required', 'array'],
            'en.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
        ];
    }
}
