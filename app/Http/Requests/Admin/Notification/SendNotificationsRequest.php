<?php

namespace App\Http\Requests\Admin\Notification;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message'   => 'required|array',
            'message.*' => 'required|string|max:255',
            'id'        => 'required',
            'class'     => ['required', 'string', Rule::in([User::class, Admin::class])],
            'user_type' => 'required|string|max:255',
            'type'      => 'nullable|string|in:mail,sms',
        ];
    }
}
