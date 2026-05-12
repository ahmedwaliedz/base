<?php

namespace App\Http\Requests\Admin\Profile;

use App\Http\Requests\Admin\BaseAdminRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseAdminRequest
{
    protected function nullableBooleanFields(): array
    {
        return ['is_notify'];
    }

    public function prepareForValidation(): void
    {
        $this->prepareNullableBooleans();
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', Rule::unique('admins')->ignore($this->user('admin')->id)],
            'country_code'  => 'required|string|max:10',
            'phone'         => ['required', 'string', Rule::unique('admins')->ignore($this->user('admin')->id)],
            'is_notify'     => ['nullable', 'boolean'],
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}