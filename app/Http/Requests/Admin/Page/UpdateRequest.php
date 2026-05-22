<?php

namespace App\Http\Requests\Admin\Page;

use App\Enums\PageType;
use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends BaseAdminRequest
{
    private const MAX_STRING_LENGTH = 'max:255';

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', self::MAX_STRING_LENGTH, Rule::unique('pages', 'slug')->ignore($this->route('page'))],
            'icon' => ['nullable', 'string', self::MAX_STRING_LENGTH],
            'type' => ['required', new Enum(PageType::class)],
            'ar' => ['required', 'array'],
            'ar.title' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'ar.content' => ['required', 'string'],
            'en' => ['required', 'array'],
            'en.title' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en.content' => ['required', 'string'],
        ];
    }
}
