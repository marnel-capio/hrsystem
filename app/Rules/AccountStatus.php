<?php

namespace App\Rules;

use App\Models\Logs;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class AccountStatus implements Rule
{
    private $message;

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        // Get user by email_address ONLY
        $user = User::where('email_address', $value)->first();

        // If user exists and is inactive
        if ($user && $user->active_status == 0) {
            Logs::create([
                'module' => 'Users',
                'activity' => "A person using this email address {$value} failed to log in.",
                'ip_address' => request()->ip(),
                'created_by' => null,
                'updated_by' => null,
                'create_time' => now(),
                'update_time' => now(),
            ]);

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
