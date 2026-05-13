<?php

namespace App\Http\Requests\Admin\Role;

use App\Services\Admin\Roles\RoleService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    private const MAX_STRING_LENGTH = 'max:255';

    public function rules(): array
    {
        $validPermissions = RoleService::getValidPermissionNames();

        return [
            'ar' => ['required', 'array'],
            'ar.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:3'],
            'en' => ['required', 'array'],
            'en.name' => ['required', 'string', self::MAX_STRING_LENGTH, 'min:3'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', self::MAX_STRING_LENGTH, Rule::in($validPermissions)],
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => __('admin/validation.one_or_more_permissions_required'),
            'permissions.*.in' => __('admin/validation.invalid_permission'),
        ];
    }
}