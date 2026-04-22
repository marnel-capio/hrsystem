<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpaceDash;
use App\Rules\MaxLength;
use App\Rules\RequiredField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIntermediateApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_type' => [new RequiredField, 'numeric'],
            'source' => ['nullable', 'numeric', 'required_if:source_type,1,2'],
            'other_source' => ['nullable', 'string', new MaxLength(80), 'required_if:source_type,3,4'],

            'last_name' => [new RequiredField, 'string', new MaxLength(80), new AlphaSpaceDash],
            'first_name' => [new RequiredField, 'string', new MaxLength(80), new AlphaSpaceDash],
            'middle_name' => [
                'nullable',
                'string',
                new MaxLength(80),
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '' && !preg_match('/^[A-Za-z\s\.]+$/', $value)) {
                        $fail('Only letters, spaces, hyphens, and period are allowed.');
                    }
                },
            ],

            'email_address' => [
                new RequiredField,
                'email',
                new MaxLength(80),
                Rule::unique('intermediate_applicants', 'email_address')->ignore($this->route('id')),
            ],

            'gender' => [new RequiredField, 'numeric', 'in:1,2'],
            'birthdate' => ['nullable', 'date'],
            'age' => [new RequiredField, 'numeric', 'min:1', 'max:99'],
            'address' => ['nullable', 'string', new MaxLength(1024)],
            'contact_no' => [new RequiredField, 'string', new MaxLength(80)],
            'school_graduated_from' => [new RequiredField, 'string', new MaxLength(80)],
            'course' => [new RequiredField, 'string', new MaxLength(80)],
            'year_attended' => [new RequiredField, 'string', new MaxLength(80)],
            'others' => ['nullable', 'string', new MaxLength(255)],

            'japanese_background' => [
                new RequiredField,
                Rule::in(array_keys(config('constants.japanese_backgrounds'))),
            ],
            'japanese_level' => [
                Rule::requiredIf($this->japanese_background == 3),
                'nullable',
                Rule::in(array_keys(config('constants.japanese_levels'))),
            ],
            'background_remarks' => ['nullable', 'string', new MaxLength(255)],

            'spouse_details' => ['nullable', 'string', new MaxLength(1024)],
            'children' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'father_details' => ['nullable', 'string', new MaxLength(1024)],
            'mother_details' => ['nullable', 'string', new MaxLength(1024)],
            'sibling_details' => ['nullable', 'string', new MaxLength(1024)],

            'emergency_contact_name' => ['nullable', 'string', new MaxLength(80)],
            'emergency_contact_number' => ['nullable', 'string', new MaxLength(80)],
            'emergency_contact_address' => ['nullable', 'string', new MaxLength(255)],

            'remarks' => ['nullable', 'string', new MaxLength(1024)],
        ];
    }

    public function messages(): array
    {
        return [
            'source.required_if' => config('errors.field_required.errorMessage'),
            'other_source.required_if' => config('errors.field_required.errorMessage'),
        ];
    }
}
