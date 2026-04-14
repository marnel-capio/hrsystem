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
        'exam_atpp_result' => 'nullable|numeric|between:0,999.99',
        'exam_git_result' => 'nullable|numeric|between:0,999.99',
        'exam_prg_result' => 'nullable|numeric|between:0,999.99',
        'exam_result' => 'nullable|integer',
        'exam_application_status' => 'nullable|integer',
        'exam_remarks' => ['nullable', 'string', new MaxLength(1024)],

'initial_interview_plan_date' => 'nullable|date|after:exam_plan_date',
        'initial_interview_actual_date' => 'nullable|date|after_or_equal:initial_interview_plan_date',
        'initial_interview_venue' => 'nullable|integer',
        'initial_interview_final' => 'nullable|numeric|between:0,999.99',
        'initial_interview_result' => 'nullable|integer',
        'initial_interview_application_status' => 'nullable|integer',
        'initial_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'final_interview_date' => 'nullable|date|after_or_equal:initial_interview_plan_date',
        'final_interview_score_1' => 'nullable|numeric|between:0,999.99',
        'final_interview_score_2' => 'nullable|numeric|between:0,999.99',
        'final_interview_score_3' => 'nullable|numeric|between:0,999.99',
        'final_interview_score_4' => 'nullable|numeric|between:0,999.99',
        'final_interview_final' => 'nullable|numeric|between:0,999.99',
        'final_interview_result' => 'nullable|integer',
        'final_interview_application_status' => 'nullable|integer',
        'final_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'job_offer_schedule' => 'nullable|date|after_or_equal:final_interview_date',
        'job_offer_status' => 'nullable|integer',
        'job_offer_remarks' => ['nullable', 'string', new MaxLength(1024)],

        'remarks' => ['nullable', 'string', new MaxLength(1024)],

        'upload_resume' => 'nullable',
        'upload_tor' => 'nullable',
        'upload_pic' => 'nullable',
    ];
}
}
