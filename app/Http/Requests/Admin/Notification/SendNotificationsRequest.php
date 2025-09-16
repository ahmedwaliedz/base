<?php

namespace App\Http\Requests\Admin\Notification;

use Illuminate\Foundation\Http\FormRequest;

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
            'class'     => 'required|string|max:255',
            'user_type' => 'required|string|max:255',
            'type'      => 'nullable|string|in:mail',
        ];
    }
}
