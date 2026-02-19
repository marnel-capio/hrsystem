<?php

namespace App\Http\Controllers;

use App\Mail\NewPasswordMail;
use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/ForgotPassword');
    }

    public function store(Request $request)
    {
        // 1️⃣ Validate email input
        $request->validate([
            'email_address' => [
                'required',
                'email',
                'max:80',
                'regex:/^[\w.-]+@awsys-i\.com$/', // AWS domain validation
                'exists:users,email_address',
            ],
        ], [
            'email_address.regex' => 'The email address must be your AWS email address.',
            'email_address.exists' => 'The email address is not registered.',
        ]);

        // 2️⃣ Find the user
        $user = User::where('email_address', $request->email_address)->first();

        // 3️⃣ Check if the account is active
        if ($user->active_status != 1) {
            return redirect()->back()->withErrors([
                'email_address' => 'Your account is no longer active. Please check it with your manager or admin.',
            ])->withInput();
        }

        // 4️⃣ Generate a strong password
        $newPassword = $this->generateStrongPassword(10);

        // 5️⃣ Update password
        $user->password = Hash::make($newPassword);
        $user->save();

        // 6️⃣ Send email
        Mail::to($user->email_address)->send(new NewPasswordMail(
            $user->first_name,
            $user->email_address,
            $newPassword
        ));

        // 7️⃣ Log activity
        Logs::create([
            'module' => 'Users',
            'activity' => "Password reset for user {$user->first_name} {$user->last_name}",
            'ip_address' => $request->ip(),
            'created_by' => null,
            'updated_by' => null,
            'create_time' => now(),
            'update_time' => now(),
        ]);

        // 8️⃣ Redirect with success
        return redirect('/login')->with('status', 'A new password has been sent to your email.');
    }

    // Strong password generator
    private function generateStrongPassword($length = 10)
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%&*_';

        $all = $upper . $lower . $numbers . $special;

        $password = [];
        $password[] = $upper[random_int(0, strlen($upper)-1)];
        $password[] = $lower[random_int(0, strlen($lower)-1)];
        $password[] = $numbers[random_int(0, strlen($numbers)-1)];
        $password[] = $special[random_int(0, strlen($special)-1)];

        for ($i = 4; $i < $length; $i++) {
            $password[] = $all[random_int(0, strlen($all)-1)];
        }

        shuffle($password);
        return implode('', $password);
    }
}
