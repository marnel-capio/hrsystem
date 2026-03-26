<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RequiredField;
use App\Rules\MaxLength;

class ActionApplicantProgrammingLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Change to true if all authenticated users can add/update
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'program_language' => [new RequiredField, 'string', new MaxLength(80)],
            'remarks' => ['nullable', 'string', new MaxLength(255)],
        ];
    }
}