<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;
use App\Models\Log;

class AWSEmailAddress implements Rule
{
    private string $messageText;
    private string $module;

    public function __construct(string $module = 'Users')
    {
        $this->module = $module;
        $this->messageText = 'Invalid email address.';
    }

    public function passes($attribute, $value): bool
    {
        $domain = substr(strrchr($value, "@"), 1) ?: '';
        if ($domain !== 'awsys-i.com') {
            $this->messageText = 'The :attribute must be your AWS email address.';
            return false;
        }

        $user = User::findByEmail($value);

        if (! $user) {
            $this->messageText = 'The email address is not registered.';
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->messageText;
    }
}
