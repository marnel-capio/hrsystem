<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Mail\NewPasswordMail;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/ForgotPassword');
    }

    public function store(EmailRequest $request)
    {
        $user = User::findByEmail($request->email_address);

        if (! $user) {
            return back()->withErrors([
                'email_address' => 'No user found with this email.',
            ]);
        }

        $newPassword = $this->generateStrongPassword();

        try {
            DB::beginTransaction();

            $user->password = Hash::make($newPassword);
            $user->save();

            // TEMPORARY: force an exception to test the catch block
            //throw new \Exception('');

            Log::createLog(
                'Users',
                "Password reset for user {$user->first_name} {$user->last_name}",
                $user->id
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::createLog(
                'Users',
                "Failed password reset for {$user->first_name} {$user->last_name}. ".$e->getMessage(),
                $user->id
            );

            return back()->withErrors([
                'email_address' => 'Failed to reset password. Please try again.',
            ]);
        }

        try {
            // Send the new password email
            Mail::to($user->email_address)->send(new NewPasswordMail(
                $user->first_name,
                $user->email_address,
                $newPassword,
                $user->id // optional for header in listener
            ));
        } catch (\Exception $e) {
            // Optional: handle failures manually if needed
        }

        return redirect('/login')->with('status', 'A new password has been sent to your email.');
    }

    private function generateStrongPassword(): string
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%&*_';
        $all = $upper.$lower.$numbers.$special;

        $password = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $special[random_int(0, strlen($special) - 1)],
        ];

        for ($i = 4; $i < 8; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($password);

        return implode('', $password);
    }
}
