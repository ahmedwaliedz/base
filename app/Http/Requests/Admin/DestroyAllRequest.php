<?php

namespace App\Http\Requests\Admin;

class DestroyAllRequest extends BaseAdminRequest
{
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'integer|min:1',
        ];
    }
}