<?php

namespace App\Rules;

use App\Models\Log;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class AccountStatus implements Rule
{
    private $message;
    private string $action; // "log in" or "send reset password link"
    private string $module;

    public function __construct(string $action = 'log in', string $module = 'Users')
    {
        $this->action = $action;
        $this->module = $module;
    }

    public function passes($attribute, $value)
    {
        // Get user by email_address ONLY
        $user = User::where('email_address', $value)->first();

        // If user exists and is inactive
        if ($user && $user->active_status == 0) {
            Log::createLog(
                $this->module,
                "A person using this email address {$value} failed to {$this->action}.",
                null
            );

            $this->message = 'Your account is no longer active. Please check it with your manager or admin.';

            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}