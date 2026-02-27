<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphaSpaceDash implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[\p{L}\s\-]+$/u', $value)) {
            $fail('Only letters, spaces, and hyphens are allowed.');
        }
    }
}