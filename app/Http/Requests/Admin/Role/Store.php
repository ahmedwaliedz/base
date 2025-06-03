<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    private const MAX_STRING_LENGTH = 'max:255';

    public function rules(): array
    {
        return [
            'ar' => [ 'required', 'array'],
            'ar.name' => [ 'required', 'string', self::MAX_STRING_LENGTH,'min:3'],
            'en' => [ 'required', 'array'],
            'en.name' => [ 'required', 'string', self::MAX_STRING_LENGTH,'min:3'],
            'permissions' => [ 'required', 'array'],
            'permissions.*' => [ 'required', 'string', self::MAX_STRING_LENGTH],
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => __('admin/validation.one_or_more_permissions_required'),
        ];
    }
}
