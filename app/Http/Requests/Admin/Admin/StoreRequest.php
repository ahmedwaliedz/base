<?php

namespace App\Http\Requests\Admin\Admin;

use App\Enums\AdminType;
use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends BaseAdminRequest
{
    public function rules()
    {
         return [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'unique:admins,email'],
            'country_code'      => ['required', 'string' , 'exists:countries,code' ,'digits_between:3,5'],
            'phone'             => ['required', 'numeric', 'unique:admins,phone' , 'regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/' , 'digits_between:9,15'],
            'password'          => ['required', Password::defaults()],
            'type'              => ['required', new Enum(AdminType::class)],
            'image'             => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_notify'         => ['nullable', 'boolean'],
            'role_id'           => ['nullable' , 'required_if:type,{AdminType::ADMIN->value}', 'exists:roles,id' , ],
        ];

    }
}
