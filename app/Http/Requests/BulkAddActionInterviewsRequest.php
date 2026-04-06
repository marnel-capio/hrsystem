<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BulkAddActionInterviewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interviewer_ids' => ['required', 'array', 'min:1'],
            'interviewer_ids.*' => ['required', 'integer', 'exists:users,id'],
            'interview_type' => [
                'required',
                'integer',
                Rule::in([
                    config('constants.interview_types.exam'),
                    config('constants.interview_types.initial'),
                    config('constants.interview_types.final'),
                ]),
            ],
            'scheduled_date' => ['required', 'date'],
        ];
    }
}
