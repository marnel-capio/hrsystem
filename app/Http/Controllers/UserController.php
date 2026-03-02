<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\Log;
use App\Models\User;
use App\Rules\RequiredField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'address' => $validated['address'],
                'contact_no' => $validated['contact_no'],
                'email_address' => $validated['email_address'],
                'password' => Hash::make($validated['password']), // IMPORTANT
                'position' => $validated['position'],
                'permissions' => $validated['permissions'],
                'active_status' => 1,
                'created_by' => auth()->id(),
                'create_time' => now(),
            ]);

            // TEMPORARY: force an exception to test the catch block
            // throw new \Exception('');

            Log::createLog(
                'Users',
                "User with {$user->email_address} email address is registered successfully.",
                $user->id
            );

            DB::commit();

            // dd([
            //     'redirecting_to' => route('user.show', $user->id),
            //     'new_user_id' => $user->id,
            //     'logged_in_id' => auth()->id(),
            //     'permission' => auth()->user()->permissions,
            // ]);

            return redirect()
                ->route('user.show', $user->id)
                ->with('success', 'Record created successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('user.index')
                ->with('error', 'An error occurred while creating the record. Please try again.');
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        $positionMap = [
            1 => 'HR Staff',
            2 => 'Technical Recruiter',
            3 => 'HR Assistant',
            4 => 'HR Senior Assistant',
            5 => 'HR Associate',
            6 => 'HR Senior Associate',
            7 => 'HR Supervisor',
            8 => 'HR Assistant Manager',
            9 => 'HR Manager',
            10 => 'BU Manager',
            11 => 'Others',
        ];

        $permissionMap = [
            1 => 'HR Admin',
            2 => 'HR Manager',
            3 => 'HR Recruiter',
            4 => 'HR',
            5 => 'BU Manager',
            6 => 'Interviewer',
            7 => 'Walk-in',
        ];

        $user->position_label = $positionMap[$user->position] ?? 'Unknown';
        $user->permission_label = $permissionMap[$user->permissions] ?? 'Unknown';

        return Inertia::render('User/UserDetail', [
            'user' => $user,
        ]);
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
