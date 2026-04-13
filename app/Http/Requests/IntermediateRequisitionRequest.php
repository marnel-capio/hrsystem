<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class IntermediateRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'engagement_type' => 'required|integer',
        'sourcing_type' => 'required|integer',
        'request_type' => 'required|integer',
        'replacement_due_to' => 'nullable|integer',
        'person_to_replace' => 'nullable|string|max:80',
        'location_assignment' => 'required|integer',
        'project_id' => 'required|integer',
        'custom_location' => 'required|string|max:1024',
        'business_unit' => 'required|string|max:20',
        'resource' => 'nullable|string|max:1024',
        'practice' => 'nullable|string|max:1024',
        'no_resources_needed' => 'nullable|integer|max:100',
        'duration_project_engagement' => 'nullable|string|max:20',
        'required_skills' => 'nullable|string|max:1024',
        'preferred_skills' => 'nullable|string|max:1024',
        'role' => 'nullable|string|max:1024|max:1024',
        'expected_salary_range' => 'nullable|string|max:80',
        'remarks' => 'nullable|string|max:1024',
        'start_date' => 'required|date|after:today',
    ];
}

    public function messages(): array
{
    return [

        'engagement_type.required' => config('errors.field_required.errorMessage'), 

        'sourcing_type.required' => config('errors.field_required.errorMessage'), 
        'request_type.required' => config('errors.field_required.errorMessage'), 
        'replacement_due_to.max' => config('errors.max_length_exceeded.errorMessage'),
        'person_to_replace.max' => config('errors.max_length_exceeded.errorMessage'),
        'custom_location.max' => config('errors.max_length_exceeded.errorMessage'),
        'location_assignment.required' => config('errors.field_required.errorMessage'), 
        'project_id.required' => config('errors.field_required.errorMessage'),

        'business_unit.required' => config('errors.field_required.errorMessage'),
        'business_unit.max' => config('errors.max_length_exceeded.errorMessage'),

        'resource.max' => config('errors.max_length_exceeded.errorMessage'),
        'practice.max' => config('errors.max_length_exceeded.errorMessage'),
        'no_resources_needed.max' => config('errors.max_length_exceeded.errorMessage'),
        'duration_project_engagement.max' => config('errors.max_length_exceeded.errorMessage'),
        'required_skills.max' => config('errors.max_length_exceeded.errorMessage'),
        'role.max' => config('errors.max_length_exceeded.errorMessage'),
        'expected_salary_range.max' => config('errors.max_length_exceeded.errorMessage'),
        'remarks.max' => config('errors.max_length_exceeded.errorMessage'),
        'start_date.after' => config('errors.start_date_after.errorMessage'),
        'start_date.required' => config('errors.field_required.errorMessage'),


    ];
}
}
