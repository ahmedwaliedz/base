<?php

namespace App\Http\Requests\Admin\Profile;

use App\Http\Requests\Admin\BaseAdminRequest;
use App\Rules\StrongPassword;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends BaseAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user('admin')->password)) {
                        $fail(__('admin/main.current_password_incorrect'));
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword],
        ];
    }
}
