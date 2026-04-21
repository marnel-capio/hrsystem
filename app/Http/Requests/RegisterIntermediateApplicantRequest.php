<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpaceDash;
use App\Rules\MaxLength;
use App\Rules\RequiredField;
use App\Rules\GenEmail;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterIntermediateApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, Rule|array|string>
     */
public function rules(): array
{
    return [
        'source_type' => [new RequiredField, 'numeric', 'between:1,4'],

        'source' => [
            'nullable',
            'numeric',
            'required_if:source_type,1,2',
            function ($attribute, $value, $fail) {
                $type = (int) request('source_type');

                if ($type === 2 && !in_array((int) $value, [1,2,3,4,5,6,7], true)) {
                    $fail('Invalid Recruitment Portal source.');
                }

                if ($type === 1 && !in_array((int) $value, [8,9,10,11,12], true)) {
                    $fail('Invalid Service Provider source.');
                }
            },
        ],

        'other_source' => [
            'nullable',
            'string',
            new MaxLength(80),
            'required_if:source_type,3',
        ],

        'japanese_background' => [new RequiredField, 'numeric', 'in:1,2,3'],

'japanese_level' => [
    'nullable',
    'numeric',
    'in:1,2,3,4,5',
    'required_if:japanese_background,3',
],

'background_remarks' => ['nullable', 'string', new MaxLength(255)],

        'last_name' => [new RequiredField, new MaxLength(80), new AlphaSpaceDash],
        'first_name' => [new RequiredField, new MaxLength(80), new AlphaSpaceDash],
        'middle_name' => ['nullable', new MaxLength(80), new AlphaSpaceDash],

        'gender' => [new RequiredField, 'numeric', 'in:1,2'],
        'birthdate' => [new RequiredField, 'string', 'max:20'],
        'age' => [new RequiredField, 'numeric', 'min:1', 'max:99'],

        'address' => ['nullable', 'string', new MaxLength(1024)],
        'email_address' => [new RequiredField, 'email', new MaxLength(80), new GenEmail],
        'contact_no' => [new RequiredField, 'string', new MaxLength(20)],

        'school_graduated_from' => ['nullable', 'string', new MaxLength(80)],
        'course' => ['nullable', 'string', new MaxLength(10)],
        'year_attended' => ['nullable', 'string', new MaxLength(10)],
        'others' => ['nullable', 'string', new MaxLength(80)],

        'spouse_details' => ['nullable', 'string', new MaxLength(1024)],
        'children' => ['nullable', 'numeric', 'min:0', 'max:99'],
        'father_details' => ['nullable', 'string', new MaxLength(1024)],
        'mother_details' => ['nullable', 'string', new MaxLength(1024)],
        'sibling_details' => ['nullable', 'string', new MaxLength(1024)],

        'emergency_contact_name' => ['nullable', 'string', new MaxLength(120)],
        'emergency_contact_number' => ['nullable', 'string', new MaxLength(20)],
        'emergency_contact_address' => ['nullable', 'string', new MaxLength(1024)],

        'remarks' => ['nullable', 'string', new MaxLength(1024)],
    ];
}

public function messages(): array
{
    return [
        'source.required_if' => config('errors.field_required.errorMessage'),
        'other_source.required_if' => config('errors.field_required.errorMessage'),
        'japanese_level.required_if' => config('errors.field_required.errorMessage'),
        'age.numeric' => config('constants.age_numeric.errorMessage'),
        'children.numeric' => 'Children must be a valid number.',
    ];
}
}
