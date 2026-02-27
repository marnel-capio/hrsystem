<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Rules\RequiredField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function create()
    {
        return Inertia::render('Register');
    }

    public function store(RegisterUserRequest $request)
    {
        try {
            $validated = $request->validated();

            User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'address' => $validated['address'],
                'contact_no' => $validated['contact_no'],
                'email_address' => $validated['email_address'],
                'password' => $validated['password'],
                'position' => $validated['position'],
                'permissions' => $validated['permissions'],
                'active_status' => 1,
                'created_by' => auth()->id(),
                'create_time' => now(),
            ]);

            return redirect()
                ->route('user.index')
                ->with('success', 'User account created successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Display a list of users
     */
    // public function index()
    // {
    //     $users = User::select(
    //         'id',
    //         'first_name',
    //         'last_name',
    //         'middle_name',
    //         'address',
    //         'contact_no',
    //         'email_address',
    //         'position',
    //         'permissions',
    //         'active_status',
    //         'create_time'
    //     )
    //         ->orderBy('last_name')
    //         ->get();

    //     return Inertia::render('User', [
    //         'users' => $users,
    //     ]);
    // }

    /**
     * Update logged-in user's account settings
     */
    // public function update(Request $request)
    // {
    //     $user = auth()->user();

    //     $validated = $request->validate([
    //         'first_name' => ['required', 'string', 'max:80', new RequiredField],
    //         'last_name' => ['required', 'string', 'max:80', new RequiredField],
    //         'middle_name' => ['nullable', 'string', 'max:80'],
    //         'address' => ['nullable', 'string', 'max:1024'],
    //         'contact_no' => ['nullable', 'string', 'max:20'],
    //         'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    //     ]);

    //     $user->first_name = $validated['first_name'];
    //     $user->last_name = $validated['last_name'];
    //     $user->middle_name = $validated['middle_name'] ?? null;
    //     $user->address = $validated['address'] ?? null;
    //     $user->contact_no = $validated['contact_no'] ?? null;

    //     if (! empty($validated['password'])) {
    //         $user->password = Hash::make($validated['password']);
    //     }

    //     $user->save();

    //     return redirect()->back()->with('success', 'Account updated successfully.');
    // }
}
