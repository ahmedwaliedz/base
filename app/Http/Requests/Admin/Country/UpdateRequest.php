<?php

namespace App\Http\Requests\Admin\Country;

use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends BaseAdminRequest
{
    private const MAX_STRING_LENGTH = 'max:255';

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', self::MAX_STRING_LENGTH, Rule::unique('countries', 'code')->ignore($this->route('country'))],
            'flag' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['required', 'boolean'],
            'ar' => ['required', 'array'],
            'ar.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en' => ['required', 'array'],
            'en.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
        ];
    }
}
