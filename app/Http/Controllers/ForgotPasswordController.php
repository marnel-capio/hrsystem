<?php

namespace App\Http\Controllers;

use App\Mail\NewPasswordMail;
use App\Models\EmailHistory;
use App\Models\Log;
use App\Models\User;
use App\Rules\AccountStatus;
use App\Rules\AWSEmailAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password page
     */
    public function create()
    {
        return Inertia::render('auth/ForgotPassword');
    }

    /**
     * Handle the password reset request
     */
    public function store(Request $request)
    {
        // Validate the email
        $request->validate([
            'email_address' => [
                'required',
                'email',
                new AWSEmailAddress('Users', 'send reset password link'),
                new AccountStatus('send reset password link')
            ],
        ]);

        // Fetch the user
        $user = User::findByEmail($request->email_address);

        // Generate a strong new password (default 8 characters)
        $newPassword = $this->generateStrongPassword();

        try {
            DB::beginTransaction();

            // Update user password
            $user->password = Hash::make($newPassword);
            $user->save();

            // Log password reset action
            Log::createLog(
                'Users',
                "Password reset for user {$user->first_name} {$user->last_name}",
                $user->id
            );

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the failure
            Log::createLog(
                'Users',
                "Failed password reset for user {$user->first_name} {$user->last_name}: " . $e->getMessage(),
                $user->id
            );

            return back()->withErrors([
                'email_address' => 'Failed to reset password. Please try again.'
            ]);
        }

        // Send email after commit
        try {
            Mail::to($user->email_address)->send(new NewPasswordMail(
                $user->first_name,
                $user->email_address,
                $newPassword
            ));

            // Log email as sent
            EmailHistory::logEmail(
                1,
                'RESET_PASSWORD',
                'Your New Password',
                'HR System',
                'no-reply@awsys-i.com',
                $user->email_address,
                "Hello {$user->first_name}, your new password is {$newPassword}",
                $user->id
            );
        } catch (\Exception $e) {
            // Log email as failed
            EmailHistory::logEmail(
                0,
                'RESET_PASSWORD',
                'Your New Password',
                'HR System',
                'no-reply@awsys-i.com',
                $user->email_address,
                "Hello {$user->first_name}, your new password is {$newPassword}",
                $user->id
            );

            return back()->withErrors([
                'email_address' => 'Password was reset but failed to send email. Please contact admin.'
            ]);
        }

        return redirect('/login')->with('status', 'A new password has been sent to your email.');
    }

    /**
     * Generate a strong password; default length of 8
     */
    private function generateStrongPassword()
    {
        $length = 8; 
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%&*_';

        $all = $upper . $lower . $numbers . $special;

        $password = [];
        // Ensure at least one of each type
        $password[] = $upper[random_int(0, strlen($upper) - 1)];
        $password[] = $lower[random_int(0, strlen($lower) - 1)];
        $password[] = $numbers[random_int(0, strlen($numbers) - 1)];
        $password[] = $special[random_int(0, strlen($special) - 1)];

        // Fill the rest randomly
        for ($i = 4; $i < $length; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($password);

        return implode('', $password);
    }
}