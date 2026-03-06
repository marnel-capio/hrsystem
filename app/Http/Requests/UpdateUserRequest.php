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