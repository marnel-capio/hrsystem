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
        'action_applicant_id' => [new RequiredField, 'exists:action_applicants,id'],
        'action_batch_id' => [new RequiredField, 'exists:action_batches,id'],

        'upload_resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', new MaxLength(5120)],
        'upload_tor' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png', new MaxLength(5120)],
        'upload_pic' => ['nullable', 'file', 'mimes:jpg,png', new MaxLength(5120)],

        'exam_plan_date' => ['nullable', 'date', 'after:today'],
        'exam_actual_date' => ['nullable', 'date', 'after_or_equal:exam_plan_date'],
        'exam_venue' => ['nullable', 'in:1,2,3,4'],
        'exam_atpp_result' => ['nullable', 'numeric', 'between:0,999.99'],
        'exam_atpp_part1_correct' => 'nullable|integer|min:0|max:999',
'exam_atpp_part1_wrong' => 'nullable|integer|min:0|max:999',
'exam_atpp_part2_correct' => 'nullable|integer|min:0|max:999',
'exam_atpp_part2_wrong' => 'nullable|integer|min:0|max:999',
'exam_atpp_part3_correct' => 'nullable|integer|min:0|max:999',
'exam_atpp_part3_wrong' => 'nullable|integer|min:0|max:999',
        'exam_git_result' => ['nullable', 'numeric', 'between:0,999.99'],
        'exam_prg_result' => ['nullable', 'numeric', 'between:0,999.99'],
        'exam_result' => ['nullable', 'in:1,2,3'],
        'exam_application_status' => ['nullable', 'in:1,3,4,5,6'],
        'exam_remarks' => ['nullable', 'string', new MaxLength(1024)],

'initial_interview_plan_date' => ['nullable', 'date', 'after:exam_plan_date'],
        'initial_interview_actual_date' => ['nullable', 'date', 'after_or_equal:initial_interview_plan_date'],
        'initial_interview_venue' => ['nullable', 'in:1,2,3,4'],
        'initial_interview_final' => ['nullable', 'numeric', 'between:0,999.99'],
        'initial_interview_result' => ['nullable', 'in:1,2,3'],
        'initial_interview_application_status' => ['nullable', 'in:1,2,3,4,5'],
        'initial_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'final_interview_date' => ['nullable', 'date', 'after_or_equal:initial_interview_plan_date'],
        'final_interview_score_1' => ['nullable', 'numeric', 'between:0,999.99'],
        'final_interview_score_2' => ['nullable', 'numeric', 'between:0,999.99'],
        'final_interview_score_3' => ['nullable', 'numeric', 'between:0,999.99'],
        'final_interview_score_4' => ['nullable', 'numeric', 'between:0,999.99'],
        'final_interview_final' => ['nullable', 'numeric', 'between:0,999.99'],
        'final_interview_result' => ['nullable', 'in:1,2,3'],
        'final_interview_application_status' => ['nullable', 'in:1,2,3,4,5'],
        'final_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'job_offer_schedule' => ['nullable', 'date', 'after_or_equal:final_interview_date'],
        'job_offer_status' => ['nullable', 'in:1,2,3,4,5,6'],
        'job_offer_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'remarks' => ['nullable', 'string', new MaxLength(1024)],
    ];
}

    public function messages(): array
    {
        $errors = config('errors');

        return [
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
