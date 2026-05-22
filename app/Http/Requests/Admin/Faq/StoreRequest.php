<?php

namespace App\Http\Requests\Admin\Faq;

use App\Enums\FaqType;
use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rules\Enum;

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
            'type'      => ['required', new Enum(FaqType::class)],
            'is_active' => ['required', 'boolean'],
            'ar' => ['required', 'array'],
            'ar.question' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'ar.answer' => ['required', 'string'],
            'en' => ['required', 'array'],
            'en.question' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:2'],
            'en.answer' => ['required', 'string'],
        ];
    }
}
