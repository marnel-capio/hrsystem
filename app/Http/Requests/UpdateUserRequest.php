<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpaceDash;
use App\Rules\AWSEmailAddress;
use App\Rules\ContactNumber;
use App\Rules\MaxLength;
use App\Rules\PasswordRules;
use App\Rules\RequiredField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Authorize this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'first_name' => ['required', 'string', new MaxLength(80), new AlphaSpaceDash, new RequiredField],
            'last_name' => ['required', 'string', new MaxLength(80), new AlphaSpaceDash, new RequiredField],
            'middle_name' => ['nullable', 'string', new MaxLength(80), new AlphaSpaceDash],
            'address' => ['required', 'string', new MaxLength(1024), new RequiredField],
            'contact_no' => ['required', 'string', 'max:20', new ContactNumber, new RequiredField],

            'email_address' => [
                'required',
                'email',
                'max:80',
                Rule::unique('users', 'email_address')->ignore($userId),
                new RequiredField,
                new AWSEmailAddress,
            ],

            // password optional when updating
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:64',
                'confirmed',
                new PasswordRules,
            ],

            'position' => ['required', 'numeric', new RequiredField],
            'permissions' => ['required', 'numeric', new RequiredField],
            'active_status' => ['required', 'boolean'],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'required' => config('errors.field_required.errorMessage'),
            'first_name.max' => config('errors.max_length_exceeded.errorMessage'),
            'last_name.max' => config('errors.max_length_exceeded.errorMessage'),
            'middle_name.max' => config('errors.max_length_exceeded.errorMessage'),
            'address.max' => config('errors.max_length_exceeded.errorMessage'),
            'email_address.unique' => config('errors.email_taken.errorMessage'),
            'password.confirmed' => config('errors.password_mismatch.errorMessage'),
        ];
    }
}