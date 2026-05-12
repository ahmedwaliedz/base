<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Support\PhoneNormalizer;

class RequestCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
                'regex:/^[\+]?[1-9][\d]{0,15}$/',
                'max:20',
            ],
            'country_code' => [
                'required',
                'string',
                'max:5',
                'exists:countries,code,is_active,1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number format is invalid.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'country_code.required' => 'Country code is required.',
            'country_code.exists' => 'Invalid country code.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => PhoneNormalizer::normalize($this->input('phone'))
            ]);
        }
        if ($this->has('country_code')) {
            $countryCode = ltrim($this->input('country_code'), '+');
            $this->merge([
                'country_code' => $countryCode
            ]);
        }
    }
}
