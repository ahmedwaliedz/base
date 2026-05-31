<?php

namespace App\Http\Requests\Admin\Post;

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
            'is_active' => ['required', 'boolean'],
            'ar' => ['required', 'array'],
            'ar.title' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'ar.content' => ['required', 'string'],
            'en' => ['required', 'array'],
            'en.title' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en.content' => ['required', 'string'],
        ];
    }
}
