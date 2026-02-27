<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ContactNumber implements Rule
{
    private string $messageText = 'Invalid contact number.';

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        // Must be numeric
        if (!is_numeric($value)) {
            $this->messageText = 'The contact number must contain only numbers.';
            return false;
        }

        // Must be exactly 11 digits
        if (strlen($value) !== 11) {
            $this->messageText = 'The contact number must be exactly 11 digits.';
            return false;
        }

        return true;
    }

    /**
     * Error message
     */
    public function message(): string
    {
        return $this->messageText;
    }
}