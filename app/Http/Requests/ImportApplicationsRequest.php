<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|mimes:xlsx,csv|max:10240',
            'batch_id' => 'required|integer|exists:action_batches,id',
        ];
    }

    public function messages(): array
    {
        $errors = config('errors');

        return [
            'file.required' => $errors['field_required']['errorMessage'],
            'batch_id.required' => $errors['field_required']['errorMessage'],
        ];
    }
}
