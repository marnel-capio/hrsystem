<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class ActionBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
 
    public function rules(): array
    {
        return [
            'action_batch' => 'required|string|max:20|unique:action_batches,action_batch',
            'target_trainees' => 'required|integer|max:99',
             'target_date' => 'required|date|after_or_equal:today',
            'remarks' => 'nullable|string|max:1024',
        ];
    }
 
    public function messages(): array
{
    return [
 
        'action_batch.required' => config('errors.action_batch_required.errorMessage'),
        'action_batch.max' => config('errors.action_batch_max.errorMessage'),
        'action_batch.unique' => config('errors.action_batch_unique.errorMessage'),
 
        'target_trainees.required' => config('errors.target_trainees_required.errorMessage'),
        'target_trainees.max' => config('errors.target_trainees_max.errorMessage'),
 
        'target_date.required' => config('errors.target_date_required.errorMessage'),
        'target_date.after_or_equal' => config('errors.target_date_after_or_equal.errorMessage'),
 
        'remarks.max' => config('errors.remarks_max.errorMessage'),
    ];
}
 
}
 