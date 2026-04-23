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
            'exam_atpp_part1_correct' => 'nullable|numeric|between:0,40',
            'exam_atpp_part1_wrong' => 'nullable|numeric|between:0,40',
            'exam_atpp_part2_correct' => 'nullable|numeric|between:0,30',
            'exam_atpp_part2_wrong' => 'nullable|numeric|between:0,30',
            'exam_atpp_part3_correct' => 'nullable|numeric|between:0,25',
            'exam_atpp_part3_wrong' => 'nullable|numeric|between:0,25',
            'exam_atpp_result' => 'nullable|numeric|between:0,95',
            'exam_git_result' => 'nullable|numeric|between:0,12',
            'exam_prg_result' => 'nullable|numeric|between:0,80',
            'exam_result' => 'nullable|integer',
            'exam_application_status' => 'nullable|integer',
            'exam_remarks' => ['nullable', 'string', new MaxLength(1024)],

            'initial_interview_assignments' => 'nullable|array',
            'initial_interview_assignments.*.id' => 'required|integer|exists:action_application_interviews,id',
            'initial_interview_assignments.*.score' => 'nullable|numeric|between:0,5',
            'initial_interview_assignments.*.evaluation_result' => 'nullable|integer|in:1,2,3,4',
            'initial_interview_assignments.*.evaluation_remarks' => ['nullable', 'string', new MaxLength(1024)],

            'initial_interview_plan_date' => 'nullable|date|after:exam_plan_date',
            'initial_interview_actual_date' => 'nullable|date|after_or_equal:initial_interview_plan_date',
            'initial_interview_venue' => 'nullable|integer',
            'initial_interview_final' => 'nullable|numeric|between:0,5',
            'initial_interview_result' => 'nullable|integer',
            'initial_interview_application_status' => 'nullable|integer',
            'initial_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

            'final_interview_date' => 'nullable|date|after_or_equal:initial_interview_plan_date',
            'final_interview_final' => 'nullable|numeric|between:0,5',
            'final_interview_result' => 'nullable|integer',
            'final_interview_application_status' => 'nullable|integer',
            'final_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

            'final_interview_assignments' => 'nullable|array',
            'final_interview_assignments.*.id' => 'required|integer|exists:action_application_interviews,id',
            'final_interview_assignments.*.score' => 'nullable|numeric|between:0,5',
'final_interview_assignments.*.evaluation_result' => 'nullable|integer|in:1,2,3,4',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            if (($data['exam_atpp_part1_correct'] ?? 0) + ($data['exam_atpp_part1_wrong'] ?? 0) > 40) {
                $validator->errors()->add('exam_atpp_part1_correct', 'Part 1 total cannot exceed 40.');
            }

            if (($data['exam_atpp_part2_correct'] ?? 0) + ($data['exam_atpp_part2_wrong'] ?? 0) > 30) {
                $validator->errors()->add('exam_atpp_part2_correct', 'Part 2 total cannot exceed 30.');
            }

            if (($data['exam_atpp_part3_correct'] ?? 0) + ($data['exam_atpp_part3_wrong'] ?? 0) > 25) {
                $validator->errors()->add('exam_atpp_part3_correct', 'Part 3 total cannot exceed 25.');
            }


        });
    }


    public function messages(): array
    {
        return [
            'final_interview_assignments.*.id.exists' => 'One of the final interview assignments is invalid.',
            'final_interview_assignments.*.score.numeric' => 'Final interviewer score must be a valid number.',
            'final_interview_assignments.*.score.between' => 'Final interviewer score must be between 0 and 5.',
            'final_interview_assignments.*.evaluation_result.in' => 'Final interviewer result must be Pending, Passed, or Failed.',
            'initial_interview_assignments.*.id.exists' => 'One of the initial interview assignments is invalid.',
            'initial_interview_assignments.*.score.numeric' => 'Initial interviewer score must be a valid number.',
            'initial_interview_assignments.*.score.between' => 'Initial interviewer score must be between 0 and 5.',
            'initial_interview_assignments.*.evaluation_result.in' => 'Initial interviewer result must be Pending, Passed, or Failed.',
        ];
    }
}
