<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordRules implements Rule
{
    public function passes($attribute, $value): bool
    {
        // At least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) return false;

        // At least one lowercase letter
        if (!preg_match('/[a-z]/', $value)) return false;

        // At least one number
        if (!preg_match('/[0-9]/', $value)) return false;

        // At least one special character !@#$%&*_
        if (!preg_match('/[!@#$%&*_]/', $value)) return false;

        // Minimum length handled by Laravel's min:8
        // Maximum length handled by Laravel's max:64

        return true;
    }

    public function message(): string
    {
        return config('errors.password_complexity_failed.errorMessage');
    }
}