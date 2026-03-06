<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsoWeekFormat implements Rule
{
    public function passes($attribute, $value): bool
    {
        // Match YYYY-W01 to YYYY-W53
        return preg_match('/^\d{4}-W(0[1-9]|[1-4][0-9]|5[0-3])$/', $value) === 1;
    }

    public function message(): string
    {
        $errors = config('errors');
        return $errors['deployment_date_format']['errorMessage'];
    }
}