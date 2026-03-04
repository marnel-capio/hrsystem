<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

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
            $this->messageText = str_replace(':attribute', $attribute, config('errors.aws_email_required.errorMessage'));
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return $this->messageText;
    }
}