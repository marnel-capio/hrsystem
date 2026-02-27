<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RequiredField;
use App\Rules\AWSEmailAddress;
use App\Rules\PasswordRules; 
use App\Rules\ContactNumber;

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
            'first_name'    => ['required', 'string', 'max:80', new RequiredField],
            'last_name'     => ['required', 'string', 'max:80', new RequiredField],
            'middle_name'   => ['nullable', 'string', 'max:80'],
            'address'       => ['required', 'string', 'max:1024', new RequiredField],
            'contact_no'    => ['required', 'string', 'max:20', new ContactNumber(), new RequiredField],
            // Custom AWSEmailAddress rule added here
            'email_address' => [
                'required',
                'email',
                'max:80',
                'unique:users,email_address',
                new RequiredField,
                //new AWSEmailAddress(), // ensures @awsys-i.com and registered
            ],
            // Custom password rule added here
            'password'      => [
                'required',
                'string',
                'min:8',
                'max:64',
                'confirmed',
                new RequiredField,
                new PasswordRules(), // enforces uppercase, lowercase, number, special char
            ],
            'position'      => ['required', 'string', 'max:80', new RequiredField],
            'permissions'   => ['required', 'integer', new RequiredField],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'required' => 'This field is required.',
            'email_address.unique' => 'This email address is already registered.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}