<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends BaseAdminRequest
{
    protected function nullableBooleanFields(): array
    {
        return ['is_notify'];
    }

    public function prepareForValidation(): void
    {
        $this->prepareNullableBooleans();
        $this->merge([
            'is_active'         => true,
            'is_complete_info'  => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'unique:users,email'],
            'country_code'      => ['required', 'string', 'exists:countries,code', 'digits_between:2,5'],
            'phone'             => ['required', 'numeric', 'unique:users,phone', 'digits_between:9,15'],
            'password'          => ['required', Password::defaults()],
            'image'             => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_notify'         => ['nullable', 'boolean'],
            'is_active'         => ['nullable', 'boolean'],
            'is_complete_info'  => ['nullable', 'boolean'],
        ];
    }
}