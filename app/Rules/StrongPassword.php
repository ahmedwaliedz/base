<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check for at least one uppercase letter
        $hasUppercase = preg_match('/[A-Z]/', $value);

        // Check for at least one lowercase letter
        $hasLowercase = preg_match('/[a-z]/', $value);

        // Check for at least one number
        $hasNumber = preg_match('/[0-9]/', $value);

        // Check for at least one symbol
        $hasSymbol = preg_match('/[^A-Za-z0-9]/', $value);

        return $hasUppercase && $hasLowercase && $hasNumber && $hasSymbol;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.strong_password');
    }
}
