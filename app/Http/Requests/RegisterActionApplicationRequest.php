<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterActionApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'action_applicant_id' => 'required|exists:action_applicants,id',
            'action_batch_id' => 'required|exists:action_batches,id',
            'trainees_from' => 'required|in:1,2',
            'upload_resume' => 'nullable|string|max:80',
            'upload_tor' => 'nullable|string|max:80',
            'upload_pic' => 'nullable|string|max:80',
        ];
    }
}
