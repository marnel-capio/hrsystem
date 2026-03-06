<?php

namespace App\Http\Requests;

use App\Rules\AccountStatus;
use App\Rules\AWSEmailAddress;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    // protected $redirect = route('login');
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email_address.exists' => 'The email address is not registered.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $customAttributes = [];
        if (strpos($this->header('referer'), route('login')) !== false) {
            $customAttributes['email_address'] = 'email';
        }

        return $customAttributes;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email_address' => [
                'bail',
                'required',
                'email',
                'max:80',
                new AWSEmailAddress,
                'exists:users,email_address',
                new AccountStatus,
            ],
            'password' => [
                'required',
                'max:64',
                'min:8',
            ],
        ];
    }
}
