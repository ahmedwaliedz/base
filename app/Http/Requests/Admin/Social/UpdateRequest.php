<?php

namespace App\Http\Requests\Admin\Social;

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
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'link' => ['nullable', 'string', self::MAX_STRING_LENGTH],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
