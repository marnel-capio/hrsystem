<?php

namespace App\Http\Requests;

use App\Rules\MaxLength;
use App\Rules\RequiredField;
use Illuminate\Foundation\Http\FormRequest;

class StoreIntermediateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Core
            'intermediate_applicant_id' => [
                new RequiredField,
            ],

            'position' => [
                new RequiredField,
                new MaxLength(80),
            ],

            'resource_schedule_id' => ['nullable', 'exists:resource_requisitions,id'],

            // Screening
            'answer_q1' => ['nullable'],
            'answer_q2' => ['nullable'],
            'answer_q3' => ['nullable'],
            'answer_q4' => ['nullable'],

            // Profile
            'availability_date' => ['nullable'],
            'desired_salary_range' => [
                'nullable',
                'regex:/^\d+(,\d{3})*-\d+(,\d{3})*$/',
            ],
            'work_preference' => ['nullable'],
            'basic_pay' => [
                'nullable',
                'regex:/^[0-9,]+$/',
            ],
            'bonuses' => ['nullable'],
            'hmo' => ['nullable'],
            'leaves' => ['nullable'],
            'allowances' => ['nullable'],
            'other_benefits' => ['nullable'],
            'targeted_company' => ['nullable'],
            'industry_experience' => ['nullable'],

            // Exam dates
            'exam_plan_date' => ['nullable', 'date'],
            'exam_actual_date' => [
                'nullable',
                'date',
                'after_or_equal:exam_plan_date',
            ],
            'exam_venue' => ['nullable', 'integer'],

            // ATPP
            'exam_atpp_part1_correct' => ['nullable', 'integer', 'min:0', 'max:40'],
            'exam_atpp_part1_wrong' => ['nullable', 'integer', 'min:0', 'max:40'],
            'exam_atpp_part2_correct' => ['nullable', 'integer', 'min:0', 'max:30'],
            'exam_atpp_part2_wrong' => ['nullable', 'integer', 'min:0', 'max:30'],
            'exam_atpp_part3_correct' => ['nullable', 'integer', 'min:0', 'max:25'],
            'exam_atpp_part3_wrong' => ['nullable', 'integer', 'min:0', 'max:25'],

            'exam_result' => ['nullable'],
            'exam_atpp_result' => ['nullable', 'numeric'],
            'exam_tech_result' => ['nullable', 'numeric', 'min:0', 'max:80'],

            'exam_application_status' => ['nullable', 'integer'],
            'exam_remarks' => [
                'nullable',
                new MaxLength(1024),
            ],

            // Initial Interview
            'initial_interview_plan_date' => ['nullable', 'date'],
            'initial_interview_actual_date' => ['nullable', 'date', 'after_or_equal:exam_plan_date',],
            'initial_interview_venue' => ['nullable', 'integer'],
            'initial_interview_final' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'initial_interview_result' => ['nullable'],
            'initial_interview_application_status' => ['nullable'],
            'initial_interview_remarks' => ['nullable'],

            // Final Interview
            'final_interview_date' => ['nullable', 'date'],
            'final_interview_final' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'final_interview_result' => ['nullable'],
            'final_interview_application_status' => ['nullable'],
            'final_interview_remarks' => ['nullable'],

            // Job Offer
            'job_offer_schedule' => ['nullable', 'date'],
            'job_offer_status' => ['nullable', 'integer'],
            'job_offer_remarks' => ['nullable'],

            // Optional remarks
            'remarks' => [
                'nullable',
                new MaxLength(1024),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'desired_salary_range.regex' => config('errors.format_invalid.errorMessage'),
            'basic_pay.regex' => config('errors.format_invalid.errorMessage'),
        ];
    }
}
