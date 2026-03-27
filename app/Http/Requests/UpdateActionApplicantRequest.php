<?php

namespace App\Http\Requests;

use App\Rules\MaxLength;
use App\Rules\RequiredField;
use Illuminate\Foundation\Http\FormRequest;

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
            'source_type' => [new RequiredField, 'integer'],
            'source' => 'nullable|integer',
            'other_source' => ['nullable', 'string', new MaxLength(80)],
            'last_name' => [new RequiredField, 'string', new MaxLength(80)],
            'first_name' => [new RequiredField, 'string', new MaxLength(80)],
            'middle_name' => ['nullable', 'string', new MaxLength(80)],
            'email_address' => [new RequiredField, 'email', new MaxLength(80) ],
            'gender' => [new RequiredField, 'integer'],
            'age' => [new RequiredField, 'integer', 'min:1', 'max:99'],
            'school' => [new RequiredField, 'string', new MaxLength(80)],
            'degree' => [new RequiredField, 'string', new MaxLength(80)],
            'others_degree' => ['nullable', 'string', new MaxLength(80)],
            'expected_graduation' => [new RequiredField, 'date'],
            'awards_recognition' => ['nullable', 'string', new MaxLength(1024)],
            'other_examination_certificate' => ['nullable', 'string', new MaxLength(1024)],
            'thesis_project' => ['nullable', 'string', new MaxLength(1024)],
            'extra_curricular' => ['nullable', 'string', new MaxLength(1024)],
            'remarks' => ['nullable', 'string', new MaxLength(1024)],
        ];
    }
}