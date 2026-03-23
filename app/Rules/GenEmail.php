<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class GenEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check that the value is a string
        if (!is_string($value)) {
            $fail("The email address must be a valid email address.");
            return;
        }

        // Check for presence of '@' and '.'
        if (strpos($value, '@') === false || strpos($value, '.') === false) {
            $fail("The email address must be a valid email address containing '@' and '.'");
            return;
        }

        // Optional: Use filter_var for stricter email validation
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail("The email address must be a valid email address.");
        }
    }
}