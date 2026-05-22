<?php

namespace App\Http\Requests\Admin\Seo;

use App\Http\Requests\Admin\BaseAdminRequest;

class UpdateRequest extends BaseAdminRequest
{
    private const MAX_STRING_LENGTH = 'max:255';

    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'ar' => ['required', 'array'],
            'ar.meta_title' => ['required', 'string', self::MAX_STRING_LENGTH],
            'ar.meta_description' => ['required', 'string'],
            'ar.meta_keywords' => ['required', 'string'],
            'en' => ['required', 'array'],
            'en.meta_title' => ['required', 'string', self::MAX_STRING_LENGTH],
            'en.meta_description' => ['required', 'string'],
            'en.meta_keywords' => ['required', 'string'],
        ];
    }
}
