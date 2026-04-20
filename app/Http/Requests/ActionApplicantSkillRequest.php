<?php

namespace App\Http\Requests;

use App\Rules\MaxLength;
use App\Rules\RequiredField;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionApplicantSkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'skill' => [
                new RequiredField,
                'string',
                new MaxLength(80),
                Rule::unique('action_applicants_skills', 'skill')
                    ->where(function ($query) {
                        return $query->where('action_applicant_id', $this->route('applicantId'));
                    })
                    ->ignore($this->route('skillId')),
            ],

            'remarks' => [
                'nullable',
                'string',
                new MaxLength(255),
            ],
        ];
    }
}