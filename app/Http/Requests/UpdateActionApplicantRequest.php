<?php

namespace App\Http\Requests;

use App\Rules\MaxLength;
use App\Rules\RequiredField;
use App\Rules\AlphaSpaceDash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActionApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You can add authorization logic here if needed
        return true;
    }

    public function rules(): array
    {
        return [
            'source_type' => [new RequiredField, 'numeric'],
            'source' => 'nullable|numeric|required_if:source_type,3',
            'other_source' => ['nullable', 'string', new MaxLength(80), 'required_if:source_type,1,2,4,5'],
            'last_name' => [new RequiredField, 'string', new MaxLength(80), new AlphaSpaceDash],
            'first_name' => [new RequiredField, 'string', new MaxLength(80), new AlphaSpaceDash],
            'middle_name' => ['nullable', 'string', new MaxLength(80), new AlphaSpaceDash],
            'email_address' => [new RequiredField, 'email', new MaxLength(80), Rule::unique('action_applicants', 'email_address')->ignore($this->route('id')) ],
            'gender' => [new RequiredField, 'numeric', 'in:1,2'],
            'age' => [new RequiredField, 'numeric', 'min:1', 'max:99'],
            'school' => [new RequiredField, 'string', new MaxLength(80)],
            'degree' => [new RequiredField, 'string', new MaxLength(80)],
            'others_degree' => ['nullable', 'string', new MaxLength(80)],
            'expected_graduation' => [new RequiredField,],
            'awards_recognition' => ['nullable', 'string', new MaxLength(1024)],
            'other_examination_certificate' => ['nullable', 'string', new MaxLength(1024)],
            'thesis_project' => ['nullable', 'string', new MaxLength(1024)],
            'extra_curricular' => ['nullable', 'string', new MaxLength(1024)],
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