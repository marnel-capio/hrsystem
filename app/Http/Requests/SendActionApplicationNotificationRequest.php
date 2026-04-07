<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendActionApplicationNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'type' => [
            'required',
            'string',
            'in:interviewer_pending_approval,applicant_scheduled,applicant_failed,hr_recruiters_job_offer',
        ],
    ];
}
}
