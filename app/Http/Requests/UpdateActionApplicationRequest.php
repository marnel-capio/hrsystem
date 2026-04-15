<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxLength;

class UpdateActionApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

public function rules(): array
{
    return [
        'exam_plan_date' => 'nullable|date|after:today',
        'exam_actual_date' => 'nullable|date|after_or_equal:exam_plan_date',
        'exam_venue' => 'nullable|integer',
        'exam_atpp_part1_correct' => 'nullable|numeric|between:0,999.99',
        'exam_atpp_part1_wrong' => 'nullable|numeric|between:0,999.99',
        'exam_atpp_part2_correct' => 'nullable|numeric|between:0,999.99',
        'exam_atpp_part2_wrong' => 'nullable|numeric|between:0,999.99',
        'exam_atpp_part3_correct' => 'nullable|numeric|between:0,999.99',
        'exam_atpp_part3_wrong' => 'nullable|numeric|between:0,999.99',
        'exam_atpp_result' => 'nullable|numeric|between:0,999.99',
        'exam_git_result' => 'nullable|numeric|between:0,999.99',
        'exam_prg_result' => 'nullable|numeric|between:0,999.99',
        'exam_result' => 'nullable|integer',
        'exam_application_status' => 'nullable|integer',
        'exam_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'initial_interview_plan_date' => 'nullable|date|after:exam_plan_date',
        'initial_interview_actual_date' => 'nullable|date|after_or_equal:initial_interview_plan_date',
        'initial_interview_venue' => 'nullable|integer',
        'initial_interview_final' => 'nullable|numeric|between:0,5',
        'initial_interview_result' => 'nullable|integer',
        'initial_interview_application_status' => 'nullable|integer',
        'initial_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'final_interview_date' => 'nullable|date|after_or_equal:initial_interview_plan_date',

        // old parent-level final score fields can stay temporarily optional during transition
        'final_interview_final' => 'nullable|numeric|between:0,5',
        'final_interview_result' => 'nullable|integer',
        'final_interview_application_status' => 'nullable|integer',
        'final_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

        // new dynamic per-interviewer final interview evaluation
        'final_interview_assignments' => 'nullable|array',
        'final_interview_assignments.*.id' => 'required|integer|exists:action_application_interviews,id',
        'final_interview_assignments.*.score' => 'nullable|numeric|between:0,5',
        'final_interview_assignments.*.evaluation_result' => 'nullable|integer|in:1,2,3',
        'final_interview_assignments.*.evaluation_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'job_offer_schedule' => 'nullable|date|after_or_equal:final_interview_date',
        'job_offer_status' => 'nullable|integer',
        'job_offer_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'remarks' => ['nullable', 'string', new MaxLength(1024)],

        'upload_resume' => 'nullable',
        'upload_tor' => 'nullable',
        'upload_pic' => 'nullable',
    ];
}

public function messages(): array
{
    return [
        'final_interview_assignments.*.id.exists' => 'One of the final interview assignments is invalid.',
        'final_interview_assignments.*.score.numeric' => 'Final interviewer score must be a valid number.',
        'final_interview_assignments.*.score.between' => 'Final interviewer score must be between 0 and 5.',
        'final_interview_assignments.*.evaluation_result.in' => 'Final interviewer result must be Pending, Passed, or Failed.',
    ];
}
}
