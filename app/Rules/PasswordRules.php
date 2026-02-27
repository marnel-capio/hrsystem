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
        return 'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (!@#$%&*_)';
    }
}