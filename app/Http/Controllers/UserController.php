<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Log;
use Illuminate\Support\Facades\Config; 

class UserController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        $users = User::getUsersForIndex();

        $positions = Config::get('constants.positions');

        return Inertia::render('user/Index', [
            'users' => $users,
            'positions' => $positions,
        ]);
    }

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

            // Log registration using LogService
            Log::createLog(
                'Users',
                "User with {$user->email_address} email address is registered successfully.",
                $user->id
            );

            DB::commit();

            return redirect()
                ->route('user.show', $user->id)
                ->with('success', config('errors.record_created_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('user.index')
                ->with('error', config('errors.transaction_failed.errorMessage'));
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

            // Update the user and get old/new snapshots for logging
            $userData = $user->updateUser($request->validated());

            // TEMPORARY: force an exception to test the catch block
            // throw new \Exception('');

            // Log the update
            $this->logService->createUserUpdateLog($userData['old'], $userData['new']);

            DB::commit();

            return redirect()
                ->route('user.show', $user->id)
                ->with('success', config('errors.user_updated_successfully.errorMessage'));

        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::back()->with('error', config('errors.update_failed.errorMessage'));
        }
    }
}
