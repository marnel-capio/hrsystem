<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActionApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_plan_date' => 'nullable|date',
            'exam_actual_date' => 'nullable|date',
            'exam_venue' => 'nullable|integer',
            'exam_atpp_result' => 'nullable|numeric',
            'exam_git_result' => 'nullable|numeric',
            'exam_prg_result' => 'nullable|numeric',
            'exam_result' => 'nullable|integer',
            'exam_application_status' => 'nullable|integer',
            'exam_remarks' => 'nullable|string',

            'initial_interview_plan_date' => 'nullable|date',
            'initial_interview_actual_date' => 'nullable|date',
            'initial_interview_venue' => 'nullable|integer',
            'initial_interview_final' => 'nullable|numeric',
            'initial_interview_result' => 'nullable|integer',
            'initial_interview_application_status' => 'nullable|integer',
            'initial_interview_remarks' => 'nullable|string',

            'final_interview_date' => 'nullable|date',
            'final_interview_sf' => 'nullable|numeric',
            'final_interview_ib' => 'nullable|numeric',
            'final_interview_rv' => 'nullable|numeric',
            'final_interview_ma' => 'nullable|numeric',
            'final_interview_final' => 'nullable|numeric',
            'final_interview_result' => 'nullable|integer',
            'final_interview_application_status' => 'nullable|integer',
            'final_interview_remarks' => 'nullable|string',

            'job_offer_schedule' => 'nullable|date',
            'job_offer_status' => 'nullable|integer',
            'job_offer_remarks' => 'nullable|string',
            'remarks' => 'nullable|string',

            'upload_resume' => 'nullable',
            'upload_tor' => 'nullable',
            'upload_pic' => 'nullable',
        ];
    }
}
