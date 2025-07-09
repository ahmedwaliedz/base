<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\Admin\BaseAdminRequest;
use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        return [
//            |unique:drivers,phone,NULL,id,company_id,' . auth()->id() . ',deleted_at,NULL',
            'email' => [ 'required', 'email', 'string', 'max:255','exists:admins,email,deleted_at,NULL'],
            'password' => ['required', 'string', 'min:6', 'max:20', new StrongPassword],
            'remember-me' => ['boolean'],
        ];
    }
}
