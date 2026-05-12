<?php

namespace App\Http\Requests\Api;

use App\Enums\LoginType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\Response\ValidationResponseTrait;
use App\Support\PhoneNormalizer;

class BaseApiRequest extends FormRequest
{
    use ValidationResponseTrait ;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeCommonInputs();
        $this->normalizeInputs();
    }

    protected function normalizeCommonInputs(): void
    {
        if ($this->filled('phone') || $this->filled('login_value')) {
            $loginValue = $this->login_value;
            if ($this->login_type() !== LoginType::EMAIL->value) {
                $loginValue = PhoneNormalizer::normalize($loginValue);
            }
            $this->merge([
                'login_value' => $loginValue,
                'country_code' => PhoneNormalizer::normalize($this->input('country_code')),
            ]);
        }
    }

    protected function normalizeInputs(): void
    {
       // do nothing
    }

}
