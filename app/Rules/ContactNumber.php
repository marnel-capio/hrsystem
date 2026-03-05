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
            $this->messageText = config('errors.contact_number_numeric.errorMessage');
            return false;
        }

        // Must be exactly 11 digits
        if (strlen($value) !== 11) {
            $this->messageText = config('errors.contact_number_length.errorMessage');
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