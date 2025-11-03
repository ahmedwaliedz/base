<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\Response\ValidationResponseTrait;

class BaseApiRequest extends FormRequest
{
    use ValidationResponseTrait ; 
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function failedValidation(Validator $validator) {
        return $this->respondValidationErrors($validator->errors()->toArray());
    }

}
