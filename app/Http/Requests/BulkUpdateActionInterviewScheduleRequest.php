<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BulkUpdateActionInterviewScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interview_ids' => ['required', 'array', 'min:1'],
            'interview_ids.*' => ['required', 'integer'],
            'scheduled_date' => ['required', 'date'],
        ];
    }
}
