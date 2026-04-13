<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MaxLength;
use App\Rules\RequiredField;


class IntermediateRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'engagement_type' => [new RequiredField, 'integer'],
        'sourcing_type' => [new RequiredField, 'integer'],
        'request_type' => [new RequiredField, 'integer'],
        'replacement_due_to' => 'nullable|integer',
        'person_to_replace' => ['nullable', 'string', new MaxLength(80)],
        'location_assignment' => [new RequiredField, 'integer'],
        'project_id' => [new RequiredField, 'integer'],
        'custom_location' => ['nullable','string',new MaxLength(1024),'required_if:location_assignment,6'],
        'business_unit' => [new RequiredField,'string',new MaxLength(20)],
        'resource' => ['nullable','string',new MaxLength(1024)],
        'practice' => ['nullable','string',new MaxLength(1024)],
        'no_resources_needed' => 'nullable|integer|max:100',
        'duration_project_engagement' => ['nullable','string',new MaxLength(20)],
        'required_skills' => ['nullable','string',new MaxLength(1024)],
        'preferred_skills' => ['nullable','string',new MaxLength(1024)],
        'role' => ['nullable','string',new MaxLength(1024)],
        'expected_salary_range' => ['nullable','string',new MaxLength(1024)],
        'remarks' => ['nullable','string',new MaxLength(1024)],
        'start_date' => [new RequiredField,'date', 'after:today'],
    ];
}

    public function messages(): array
{
    return [

        'custom_location.required_if' => config('errors.field_required.errorMessage'),
        'start_date.after' => config('errors.start_date_after.errorMessage'),
        'no_resources_needed.max' => config('errors.max_length_exceeded.errorMessage'),



    ];
}
}
