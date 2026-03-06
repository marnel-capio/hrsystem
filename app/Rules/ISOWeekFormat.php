<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsoWeekFormat implements Rule
{
    public function passes($attribute, $value): bool
    {
        // Validates YYYY-WW format
        return preg_match('/^\d{4}-W\d{2}$/', $value) === 1;
    }

    public function message(): string
    {
        $errors = config('errors');
        return $errors['DEPLOYMENT_DATE_FORMAT']['errorMessage'];
    }
}