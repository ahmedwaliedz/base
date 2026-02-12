<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\LoginType;
use App\Http\Requests\Api\BaseApiRequest;

class PasswordLoginRequest extends BaseApiRequest
{

    public function login_type(): string
    {
        $isEmail = filter_var($this->login_value, FILTER_VALIDATE_EMAIL);
        $isNumeric = ctype_digit(ltrim($this->login_value, '+'));
        return ($isEmail || !$isNumeric) ? LoginType::EMAIL->value : LoginType::PHONE->value;
    }

    public function rules(): array
    {
        if ($this->login_type() === LoginType::EMAIL->value) {
            return [
                'login_value'  => ['required', 'string', 'email:rfc,dns', 'max:255', 'exists:users,email,deleted_at,NULL'],
                'password'     => ['required', 'string', 'min:6'],
            ];
        }else{
            return [
                'login_value'  => ['required', 'digits_between:9,15', 'regex:/^[\+]?[1-9][\d]{0,15}$/', 'max:20', 'exists:users,phone,deleted_at,NULL'],
                'country_code' => ['required', 'string', 'max:5', 'exists:countries,code,deleted_at,NULL'],
                'password'     => ['required', 'string', 'min:6'],
            ];
        }
    }

    public function messages(): array
    {
        return [
            'login_value.exists' => __('api/auth.invalid_credentials'),
        ];
    }

    public function attributes(): array
    {
        return [
            'login_value' => LoginType::label(LoginType::from($this->login_type())),
        ];
    }
}
