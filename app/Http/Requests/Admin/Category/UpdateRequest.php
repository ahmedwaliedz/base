<?php

namespace App\Http\Requests\Admin\Category;

use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rule;

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
            'slug' => ['required', 'string', self::MAX_STRING_LENGTH, Rule::unique('categories', 'slug')->ignore($this->route('category'))],
            'icon' => ['nullable', 'string', self::MAX_STRING_LENGTH],
            'is_active' => ['required', 'boolean'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'ar' => ['required', 'array'],
            'ar.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en' => ['required', 'array'],
            'en.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
        ];
    }
}
