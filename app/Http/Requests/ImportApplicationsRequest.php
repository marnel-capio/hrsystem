<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RequiredField;

class ImportApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

        public function rules(): array
        {
            return [
                'file' => [new RequiredField, 'mimes:xlsx,csv', 'max:10240'],
                'batch_id' => [new RequiredField, 'integer', 'exists:action_batches,id'],
            ];
        }

    public function messages(): array
    {
        $errors = config('errors');

        return [
            'file.required' => $errors['field_required']['errorMessage'],
            'batch_id.required' => $errors['field_required']['errorMessage'],
            'file.max' => $errors['file_too_large']['errorMessage'],
            'file.mimes' => $errors['corrupted_file']['errorMessage'] ?? 'Invalid file type. File may be corrupted.',
        ];
    }
}
