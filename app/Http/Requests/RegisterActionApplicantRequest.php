<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpaceDash;
use App\Rules\MaxLength;
use App\Rules\RequiredField;
use App\Rules\GenEmail;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterActionApplicantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change to true if any authenticated user can register an applicant
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {

        // Use RequiredField for mandatory fields to centralize "not empty" logic
        return [
            'source_type' => [new RequiredField, 'numeric', 'between:1,5'],

            'source' => [
                'nullable',
                'numeric',
                'required_if:source_type,3',
            ],
            'other_source' => [
                'nullable',
                'string',
                new MaxLength(80),
                'required_if:source_type,1,2,4,5',
            ],

            'last_name' => [new RequiredField, new MaxLength(80), new AlphaSpaceDash],
            'first_name' => [new RequiredField, new MaxLength(80), new AlphaSpaceDash],
            'middle_name' => ['nullable', new MaxLength(80), new AlphaSpaceDash],
            'email_address' => [new RequiredField, 'email', new MaxLength(80), new GenEmail,],
            'gender' => [new RequiredField, 'numeric', 'in:1,2'],
            'age' => [new RequiredField, 'numeric', 'min:1', 'max:99'],
            'school' => [new RequiredField, 'string', new MaxLength(80)],
            'degree' => [new RequiredField, 'string', new MaxLength(80)],
            'others_degree' => ['nullable', 'string', new MaxLength(80)],
            'expected_graduation' => [new RequiredField, 'string', 'max:20'],
            'awards_recognition' => ['nullable', 'string', new MaxLength(1024)],
            'other_examination_certificate' => ['nullable', 'string', new MaxLength(1024)],
            'thesis_project' => ['nullable', 'string', new MaxLength(1024)],
            'extra_curricular' => ['nullable', 'string', new MaxLength(1024)],
            'remarks' => ['nullable', 'string', new MaxLength(1024)],
        ];
    }

    /**
     * Custom messages (optional)
     */
    public function messages(): array
    {
        return [
            'source.required_if' => config('errors.field_required.errorMessage'),
            'other_source.required_if' => config('errors.field_required.errorMessage'),
            'email_address.unique' => 'This email has already been registered.',
            'age.numeric' => config('constants.age_numeric.errorMessage'),
        ];
    }
}
