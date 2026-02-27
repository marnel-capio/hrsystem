<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AWSEmailAddress;
use App\Rules\AccountStatus;

class EmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all users to request password reset
    }

    public function rules(): array
    {
        return [
            'email_address' => [
                'required',
                'email',
                'max:80',
                new AWSEmailAddress('Users'),
                new AccountStatus('send reset password link'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email_address.required' => 'This field is required.',
            'email_address.email' => 'The email address must be a valid email address.',
            'email_address.max' => 'The email address must not be greater than 80 characters.',
        ];
    }
}