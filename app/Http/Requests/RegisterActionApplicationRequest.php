<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RequiredField;
use App\Rules\MaxLength;

class RegisterActionApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only users with permissions 1, 2, or 3 can create
        return in_array(auth()->user()->permissions, [1, 2, 3]);
    }

    public function rules(): array
    {
        return [
            // Basic Information - Required fields with custom rule
            'action_applicant_id' => [new RequiredField, 'exists:action_applicants,id'],
            'action_batch_id' => [new RequiredField, 'exists:action_batches,id'],

            // File Uploads
            'upload_resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', new MaxLength(5120)], // 5MB
            'upload_tor' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png', new MaxLength(5120)], // 5MB
            'upload_pic' => ['nullable', 'file', 'mimes:jpg,png', new MaxLength(5120)], // 2MB

            // Exam Details
            'exam_plan_date' => ['nullable', 'date'],
            'exam_actual_date' => ['nullable', 'date'],
            'exam_venue' => ['nullable', 'in:1,2,3,4'],
            'exam_atpp_result' => ['nullable', 'numeric', 'between:0,999.99'],
            'exam_git_result' => ['nullable', 'numeric', 'between:0,999.99'],
            'exam_prg_result' => ['nullable', 'numeric', 'between:0,999.99'],
            'exam_result' => ['nullable', 'in:1,2,3'],
            'exam_application_status' => ['nullable', 'in:1,2,3,4,5,6,7'],
            'exam_remarks' => ['nullable', 'string', new MaxLength(1024)],

            // Initial Interview
            'initial_interview_plan_date' => ['nullable', 'date'],
            'initial_interview_actual_date' => ['nullable', 'date'],
            'initial_interview_venue' => ['nullable', 'in:1,2,3,4'],
            'initial_interview_final' => ['nullable', 'numeric', 'between:0,999.99'],
            'initial_interview_result' => ['nullable', 'in:1,2,3'],
            'initial_interview_application_status' => ['nullable', 'in:1,2,3,4,5'],
            'initial_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

            // Final Interview
            'final_interview_date' => ['nullable', 'date'],
            'final_interview_sf' => ['nullable', 'numeric', 'between:0,999.99'],
            'final_interview_ib' => ['nullable', 'numeric', 'between:0,999.99'],
            'final_interview_rv' => ['nullable', 'numeric', 'between:0,999.99'],
            'final_interview_ma' => ['nullable', 'numeric', 'between:0,999.99'],
            'final_interview_final' => ['nullable', 'numeric', 'between:0,999.99'],
            'final_interview_result' => ['nullable', 'in:1,2,3'],
            'final_interview_application_status' => ['nullable', 'in:1,2,3,4,5'],
            'final_interview_remarks' => ['nullable', 'string', new MaxLength(1024)],

            // Job Offer
            'job_offer_schedule' => ['nullable', 'date'],
            'job_offer_status' => ['nullable', 'in:1,2,3,4,5,6'],
            'job_offer_remarks' => ['nullable', 'string', new MaxLength(1024)],

            // General
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
        ];
    }
}