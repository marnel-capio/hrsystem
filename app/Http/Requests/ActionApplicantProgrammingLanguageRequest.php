<?php

namespace App\Http\Requests;

use App\Rules\MaxLength;
use App\Rules\RequiredField;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionApplicantProgrammingLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change to true if all authenticated users can add/update
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
            'program_language' => [
                new RequiredField,
                'string',
                new MaxLength(80),
                Rule::unique('action_applicants_programming_languages', 'program_language')
                    ->where(function ($query) {
                        return $query->where('action_applicant_id', $this->route('applicantId'));
                    })
                    ->ignore($this->route('langId')), // IMPORTANT: use langId
            ],
            'remarks' => ['nullable', 'string', new MaxLength(255)],
        ];
    }
}
