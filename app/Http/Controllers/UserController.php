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
use Illuminate\Support\Facades\Config; 

class UserController extends Controller
{

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
    
}
