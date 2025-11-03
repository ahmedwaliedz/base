<?php

namespace App\Http\Requests\Api\Auth;

use App\Support\PhoneNormalizer;
use App\Http\Requests\Api\BaseApiRequest;

class PasswordLoginRequest extends BaseApiRequest
{

    public function rules(): array
    {
        return [
            // 'phone' => ['required','string','regex:/^[\+]?[1-9][\d]{0,15}$/','max:20',],
            // 'password' => ['required','string','min:6'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => PhoneNormalizer::normalize($this->input('phone'))
            ]);
        }
    }
}
