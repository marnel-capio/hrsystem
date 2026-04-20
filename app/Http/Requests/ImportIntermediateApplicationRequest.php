<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RequiredField;

class ImportIntermediateApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
        {
            return [
                'file' => [new RequiredField, 'mimes:xlsx,csv', 'max:10240'],
            ];
        }

    public function messages(): array
    {
        $errors = config('errors');

        return [
            'file.max' => $errors['file_too_large']['errorMessage'],
            'file.mimes' => $errors['corrupted_file']['errorMessage'] ?? 'Invalid file type. File may be corrupted.',
        ];
    }

}
