<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Logs;

class AWSEmailAddress implements Rule
{
    private $value; // store value for logging

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->value = $value; // save for logging

        $offset = strpos($value, '@') === false ? -11 : strpos($value, '@') + 1;
        $domain = substr($value, $offset);

        // If domain is wrong, log it
        if ($domain !== 'awsys-i.com') {
            Logs::create([
                'module' => 'Users',
                'activity' => "A person using this email address {$value} failed to log in.",
                'ip_address' => request()->ip(),
                'created_by' => null,
                'updated_by' => null,
                'create_time' => now(),
                'update_time' => now(),
            ]);
        }

        return $domain === 'awsys-i.com';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be your AWS email address.';
    }
}
