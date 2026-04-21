<?php

namespace App\Http\Requests;

use App\Rules\MaxLength;
use App\Rules\RequiredField;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IntermediateApplicantSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'skill' => [
                new RequiredField,
                'string',
                new MaxLength(80),
                Rule::unique('intermediate_applicants_skills', 'skill')
                    ->where(function ($query) {
                        return $query
                            ->where('intermediate_applicant_id', $this->route('applicantId'))
                            ->where('is_deleted', 0);
                    })
                    ->ignore($this->route('skillId')),
            ],
            'remarks' => ['nullable', 'string', new MaxLength(255)],
        ];
    }
}
