<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BaseAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function nullableBooleanFields(): array
    {
        return [];
    }

    protected function normalizeBooleanInput(string $key): void
    {
        $value = $this->input($key);

        if ($value === '' || is_null($value)) {
            $this->merge([$key => null]);
            return;
        }

        $normalized = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($normalized !== null) {
            $this->merge([$key => $normalized]);
        }
    }

    protected function prepareNullableBooleans(): void
    {
        foreach ($this->nullableBooleanFields() as $field) {
            $this->normalizeBooleanInput($field);
        }
    }

    protected function filterNullBooleans(array $validated): array
    {
        foreach ($validated as $key => $value) {
            if ($value === null && in_array($key, $this->nullableBooleanFields(), true)) {
                unset($validated[$key]);
            }
        }

        return $validated;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        return is_array($validated) ? $this->filterNullBooleans($validated) : $validated;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}