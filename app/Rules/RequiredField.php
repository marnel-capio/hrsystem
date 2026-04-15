<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredField implements Rule
{
    public function passes($attribute, $value)
    {
        return !is_null($value) && trim($value) !== '';
    }

    public function message()
    {
        return config('errors.field_required.errorMessage');
    }
}
