<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;
use App\Models\Log;

class AccountStatus implements Rule
{
    private string $message;
    private string $action;
    private string $module;

    public function __construct(string $action = 'log in', string $module = 'Users')
    {
        $this->action = $action;
        $this->module = $module;
    }

    public function passes($attribute, $value)
    {
        $user = User::findByEmail($value);

        if ($user && $user->active_status == 0) {
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