<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphaSpaceDash implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[\p{L}\s\-]+$/u', $value)) {
            $message = config('errors.alpha_space_dash.errorMessage');
            $fail($message);
        }
    }
}