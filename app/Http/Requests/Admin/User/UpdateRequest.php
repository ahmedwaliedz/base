<?php
namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends BaseAdminRequest {

    public function prepareForValidation() {
        $this->merge([
            'is_notify' => boolval($this->is_notify),
        ]);
    }

    public function rules() {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email,' . $this->user],
            'country_code' => ['required', 'string', 'exists:countries,code', 'digits_between:3,5'],
            'phone'        => ['required', 'numeric', 'unique:users,phone,' . $this->user/* , 'regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/' */, 'digits_between:9,15'],
            'password'     => ['nullable', Password::defaults()],
            'image'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_notify'    => ['nullable', 'boolean'],
        ];

    }
}
