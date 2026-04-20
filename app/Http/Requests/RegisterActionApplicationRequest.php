<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RequiredField;
use App\Rules\MaxLength;

class RegisterActionApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->permissions, [1, 2, 3]);
    }

public function rules(): array
{
    return [
        'action_applicant_id' => 'required|integer|exists:action_applicants,id',
        'action_batch_id' => 'required|integer|exists:action_batches,id',

        'exam_plan_date' => 'nullable|date|after:today',
        'exam_actual_date' => 'nullable|date|after_or_equal:exam_plan_date',
        'exam_venue' => 'nullable|integer',
        'exam_atpp_part1_correct' => 'nullable|numeric|min:0',
        'exam_atpp_part1_wrong' => 'nullable|numeric',
        'exam_atpp_part2_correct' => 'nullable|numeric|min:0',
        'exam_atpp_part2_wrong' => 'nullable|numeric|min:0',
        'exam_atpp_part3_correct' => 'nullable|numeric|min:0',
        'exam_atpp_part3_wrong' => 'nullable|numeric|min:0',
        'exam_atpp_result' => 'nullable|numeric|between:0,95',
        'exam_git_result' => 'nullable|numeric|between:0,12',
        'exam_prg_result' => 'nullable|numeric|between:0,80',
        'exam_result' => 'nullable|integer',
        'exam_application_status' => 'nullable|integer',
        'exam_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'initial_interview_assignments' => 'nullable|array',
'initial_interview_assignments.*.id' => 'required|integer|exists:action_application_interviews,id',
'initial_interview_assignments.*.score' => 'nullable|numeric|between:0,5',
'initial_interview_assignments.*.evaluation_result' => 'nullable|integer|in:1,2,3',
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
        $errors = config('errors');

        return [

        'initial_interview_assignments.*.id.exists' => 'One of the initial interview assignments is invalid.',
'initial_interview_assignments.*.score.numeric' => 'Initial interviewer score must be a valid number.',
'initial_interview_assignments.*.score.between' => 'Initial interviewer score must be between 0 and 5.',
'initial_interview_assignments.*.evaluation_result.in' => 'Initial interviewer result must be Pending, Passed, or Failed.',

             'final_interview_assignments.*.id.exists' => 'One of the final interview assignments is invalid.',
        'final_interview_assignments.*.score.numeric' => 'Final interviewer score must be a valid number.',
        'final_interview_assignments.*.score.between' => 'Final interviewer score must be between 0 and 5.',
        'final_interview_assignments.*.evaluation_result.in' => 'Final interviewer result must be Pending, Passed, or Failed.',

            'action_applicant_id.required' => $errors['field_required']['errorMessage'],
            'action_batch_id.required' => $errors['field_required']['errorMessage'],

            'upload_resume.max' => $errors['file_too_large']['errorMessage'],
            'upload_tor.max' => $errors['file_too_large']['errorMessage'],
            'upload_pic.max' => $errors['file_too_large']['errorMessage'],

            'upload_resume.mimes' => $errors['corrupted_file']['errorMessage'],
            'upload_tor.mimes' => $errors['corrupted_file']['errorMessage'],
            'upload_pic.mimes' => $errors['corrupted_file']['errorMessage'],

            'exam_atpp_result.between' => $errors['max_length_exceeded']['errorMessage'],
            'exam_git_result.between' => $errors['max_length_exceeded']['errorMessage'],
            'exam_prg_result.between' => $errors['max_length_exceeded']['errorMessage'],

            'initial_interview_final.between' => $errors['max_length_exceeded']['errorMessage'],

            'final_interview_score_1.between' => $errors['max_length_exceeded']['errorMessage'],
            'final_interview_score_2.between' => $errors['max_length_exceeded']['errorMessage'],
            'final_interview_score_3.between' => $errors['max_length_exceeded']['errorMessage'],
            'final_interview_score_4.between' => $errors['max_length_exceeded']['errorMessage'],
            'final_interview_final.between' => $errors['max_length_exceeded']['errorMessage'],

            'exam_plan_date.after' => 'Exam Plan Date must be later than today.',
'exam_actual_date.after_or_equal' => 'Actual Exam Date must be on or after Exam Plan Date.',
'initial_interview_actual_date.after_or_equal' => 'Actual Interview Date must be on or after Initial Interview Plan Date.',
'final_interview_date.after_or_equal' => 'Final Interview Date must be on or after Initial Interview Plan Date.',
'job_offer_schedule.after_or_equal' => 'Job Offer Schedule must be on or after Final Interview Date.',
        ];
    }
}
