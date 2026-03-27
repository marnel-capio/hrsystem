<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterActionApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        $allowedRoles = ['HR_ADMIN', 'HR_MANAGER', 'HR_RECRUITER'];
        return in_array(auth()->user()->role, $allowedRoles);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            // Basic Information - Required fields
            'action_applicant_id' => 'required|exists:action_applicants,id',
            'action_batch_id' => 'required|exists:action_batches,id',

            // File Uploads
            'upload_resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'upload_tor' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120', // 5MB
            'upload_pic' => 'nullable|file|mimes:jpg,png|max:2048', // 2MB

            // Exam Details
            'exam_plan_date' => 'nullable|date',
            'exam_actual_date' => 'nullable|date',
            'exam_venue' => 'nullable|in:1,2,3,4',
            'exam_atpp_result' => 'nullable|numeric|between:0,999.99',
            'exam_git_result' => 'nullable|numeric|between:0,999.99',
            'exam_prg_result' => 'nullable|numeric|between:0,999.99',
            'exam_result' => 'nullable|in:1,2,3',
            'exam_application_status' => 'nullable|in:1,2,3,4,5,6,7',
            'exam_remarks' => 'nullable|string|max:1024',

            // Initial Interview
            'initial_interview_plan_date' => 'nullable|date',
            'initial_interview_actual_date' => 'nullable|date',
            'initial_interview_venue' => 'nullable|in:1,2,3,4',
            'initial_interview_final' => 'nullable|numeric|between:0,999.99',
            'initial_interview_result' => 'nullable|in:1,2,3',
            'initial_interview_application_status' => 'nullable|in:1,2,3,4,5',
            'initial_interview_remarks' => 'nullable|string|max:1024',

            // Final Interview
            'final_interview_date' => 'nullable|date',
            'final_interview_sf' => 'nullable|numeric|between:0,999.99',
            'final_interview_ib' => 'nullable|numeric|between:0,999.99',
            'final_interview_rv' => 'nullable|numeric|between:0,999.99',
            'final_interview_ma' => 'nullable|numeric|between:0,999.99',
            'final_interview_final' => 'nullable|numeric|between:0,999.99',
            'final_interview_result' => 'nullable|in:1,2,3',
            'final_interview_application_status' => 'nullable|in:1,2,3,4,5',
            'final_interview_remarks' => 'nullable|string|max:1024',

            // Job Offer
            'job_offer_schedule' => 'nullable|date',
            'job_offer_status' => 'nullable|in:1,2,3,4,5,6',
            'job_offer_remarks' => 'nullable|string|max:1024',

            // General
            'remarks' => 'nullable|string|max:1024',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'action_applicant_id.required' => 'ACTION Applicant is required.',
            'action_batch_id.required' => 'ACTION Batch is required.',
            'upload_resume.max' => 'File size must be less than 5MB.',
            'upload_tor.max' => 'File size must be less than 5MB.',
            'upload_pic.max' => 'File size must be less than 2MB.',
            'upload_resume.mimes' => 'Resume must be a PDF or Word document.',
            'upload_tor.mimes' => 'TOR must be a PDF, Word document, or image.',
            'upload_pic.mimes' => 'Picture must be a JPG or PNG image.',
        ];
    }
}
