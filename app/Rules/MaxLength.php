<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxLength implements ValidationRule
{
    protected int $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (mb_strlen($value) > $this->max) {
            $fail("This field exceeds the maximum allowed length.");
        }
    }
}