<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function create()
    {
        return Inertia::render('user/Register', [
            'positions' => config('constants.positions'),
            'permissions' => config('constants.permissionsList'),
        ]);
    }

    public function store(RegisterUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::register($request->validated());

            // TEMPORARY: force an exception to test the catch block
            // throw new \Exception('');

            Log::createLog(
                'Users',
                "User with {$user->email_address} email address is registered successfully.",
                $user->id
            );

            DB::commit();

            $successMessage = config('errors.record_created_successfully.errorMessage');

            return redirect()
                ->route('user.show', $user->id)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = config('errors.transaction_failed.errorMessage');

            return redirect()
                ->route('user.index')
                ->with('error', $errorMessage);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('user/UserDetail', [
            'user' => $user,
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('user/Edit', [
            'user' => $user,
            'positions' => config('constants.positions'),
            'permissions' => config('constants.permissionsList'),
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            // Step 1: old snapshot
            $oldData = $user->getOriginal();

            // Step 2: validated data
            $data = $request->validated();

            // Keep raw password for logging
            $rawPassword = $request->input('password');

            // Only hash and update password if entered
            if ($request->filled('password')) {
                $data['password'] = Hash::make($rawPassword);
            } else {
                unset($data['password']);
            }

            $customUserId = auth()->id();
            $data['updated_by'] = $customUserId;

            // Step 3: update user
            $user->update($data);

            // Step 4: Prepare new data for logging
            $newData = $user->fresh()->toArray();
            if ($request->filled('password')) {
                $newData['password'] = $rawPassword; // raw for log
            } else {
                unset($newData['password']);
            }

            // Step 5: create log
            Log::createUserUpdateLog($oldData, $newData);

            DB::commit();

            return redirect()
                ->route('user.show', $user->id)
                ->with('success', config('errors.user_updated_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('user.index')
                ->with('error', config('errors.transaction_failed.errorMessage'));
        }
    }
}
