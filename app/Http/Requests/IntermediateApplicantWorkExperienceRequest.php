<?php

namespace App\Http\Requests;

use App\Rules\MaxLength;
use Illuminate\Foundation\Http\FormRequest;

class IntermediateApplicantWorkExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employer' => ['nullable', 'string', new MaxLength(80)],
            'company_address' => ['nullable', 'string', new MaxLength(80)],
            'job_title' => ['nullable', 'string', new MaxLength(80)],
            'date_employed' => ['nullable', 'string', new MaxLength(40)],
            'work_description' => ['nullable', 'string', new MaxLength(1024)],
            'salary' => ['nullable', 'string', new MaxLength(40)],
            'reason_for_leaving' => ['nullable', 'string', new MaxLength(1024)],
            'name_supervisor' => ['nullable', 'string', new MaxLength(80)],
            'remarks' => ['nullable', 'string', new MaxLength(1024)],
        ];
    }
}
