<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Log;
use App\Models\User;

class AWSEmailAddress implements Rule
{
    private string $messageText; // store dynamic error message
    private string $module;

    public function __construct(string $module = 'Users')
    {
        $this->module = $module;
        $this->messageText = 'Invalid email address.';
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        // Check AWS domain
        $domain = substr(strrchr($value, "@"), 1) ?: '';
        if ($domain !== 'awsys-i.com') {
            $this->messageText = 'The :attribute must be your AWS email address.';

            // Log failed attempt
            Log::createLog(
                $this->module,
                "A person using this email address {$value} failed validation (invalid AWS domain).",
                null
            );

            return false;
        }

        // Check if email exists in the database
        $user = User::where('email_address', $value)->first();
        if (! $user) {
            $this->messageText = 'The email address is not registered.';

            // Log failed attempt
            Log::createLog(
                $this->module,
                "A person using this email address {$value} failed validation (email not registered).",
                null
            );

            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return $this->messageText;
    }
}