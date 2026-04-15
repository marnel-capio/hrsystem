<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitActionInterviewDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'in:accept,decline'],
            'reason' => ['nullable', 'string', 'max:1024'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (
                $this->input('decision') === 'decline' &&
                !filled($this->input('reason'))
            ) {
                $validator->errors()->add('reason', 'Reason is required when declining.');
            }
        });
    }
}
