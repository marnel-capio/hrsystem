<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email_address' => ['required', 'string', 'email', 'max:255', 'unique:users,email_address'],
            'password' => ['required', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_no' => ['nullable', 'string', 'max:20'],
        ])->validate();

        return User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'middle_name' => $input['middle_name'] ?? null,
            'email_address' => $input['email_address'],
            'password' => Hash::make($input['password']),
            'address' => $input['address'] ?? null,
            'contact_no' => $input['contact_no'] ?? null,

            'position' => $input['position'] ?? 'Employee',
            'permissions' => $input['permissions'] ?? 'user',
            'active_status' => $input['active_status'] ?? 1,

            'created_by' => null, // <-- foreign key allows null
            'updated_by' => null, // <-- foreign key allows null
        ]);

    }
}
