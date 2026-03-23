<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IntermediateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_name' => [
                'required',
                'string',
                'max:20',
                // Unique validation removed
            ],
            'remarks' => 'nullable|string|max:1024',
        ];
    }

    public function messages(): array
    {
        return [
            'project_name.required' => config('errors.field_required.errorMessage'),
            'project_name.max' => config('errors.max_length_exceeded.errorMessage'),
            'remarks.max' => config('errors.max_length_exceeded.errorMessage'),
        ];
    }
}