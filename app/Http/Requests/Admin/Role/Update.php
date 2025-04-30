<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public function rules(): array
    {
        return [
            'ar' => [ 'required', 'array'],
            'ar.name' => [ 'required', 'string', 'max:255','min:3'],
            'en' => [ 'required', 'array'],
            'en.name' => [ 'required', 'string', 'max:255','min:3'],
            'permissions' => [ 'required', 'array'],
            'permissions.*' => [ 'required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => __('admin/validation.one_or_more_permissions_required'),
        ];
    }
}
