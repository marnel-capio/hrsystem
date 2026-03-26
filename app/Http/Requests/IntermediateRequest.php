<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class IntermediateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $projectId = $this->route('id') ?? null;

        return [
            'project_name' => [
                'required',
                'string',
                'max:20',
                $projectId
                    ? Rule::unique('projects', 'project_name')->ignore($projectId)
                    : Rule::unique('projects', 'project_name'),
            ],
            'remarks' => 'nullable|string|max:1024',
        ];
    }

    public function messages(): array
    {
        return [
            'project_name.unique' => config('errors.project_name_unique.errorMessage'),
            'project_name.required' => config('errors.field_required.errorMessage'),
            'project_name.max' => config('errors.max_length_exceeded.errorMessage'),
            'remarks.max' => config('errors.max_length_exceeded.errorMessage'),
        ];
    }
}