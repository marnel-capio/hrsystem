<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpaceDash;
use App\Rules\AWSEmailAddress;
use App\Rules\ContactNumber;
use App\Rules\MaxLength;
use App\Rules\PasswordRules;
use App\Rules\RequiredField;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
        return [
            'first_name' => ['required', 'string', new MaxLength(80), new AlphaSpaceDash, new RequiredField],
            'last_name' => ['required', 'string', new MaxLength(80), new AlphaSpaceDash, new RequiredField],
            'middle_name' => ['nullable', 'string', new MaxLength(80), new AlphaSpaceDash],
            'address' => ['required', 'string', new MaxLength(1024), new RequiredField],
            'contact_no' => ['required', 'string', 'max:20', new ContactNumber, new RequiredField],
            // Custom AWSEmailAddress rule added here
            'email_address' => [
                'required',
                'email',
                'max:80',
                'unique:users,email_address',
                new RequiredField,
                new AWSEmailAddress, // ensures @awsys-i.com
            ],
            // Custom password rule added here
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'confirmed',
                new RequiredField,
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
            'required' => 'This field is required.',
            'first_name.max' => 'This field exceeds the maximum allowed length.',
            'last_name.max' => 'This field exceeds the maximum allowed length.',
            'middle_name.max' => 'This field exceeds the maximum allowed length.',
            'address.max' => 'This field exceeds the maximum allowed length.',
            'email_address.unique' => 'This email address is already registered.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
