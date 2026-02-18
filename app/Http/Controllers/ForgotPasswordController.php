<?php

namespace App\Http\Controllers;

use App\Mail\NewPasswordMail;
use App\Models\User;
use App\Models\Logs; // Make sure you have this model for your logs table
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    // Show the login page
    public function createLogin()
    {
        return Inertia::render('Login'); // points to resources/js/pages/Login.vue
    }

    // Show the forgot password form
    public function create()
    {
        return Inertia::render('auth/ForgotPassword');
    }

    // Handle the forgot password form submission
    public function store(Request $request)
    {
        // 1️⃣ Validate the email_address input
        $request->validate([
            'email_address' => 'required|email|exists:users,email_address',
        ]);

        // 2️⃣ Find the user by email_address
        $user = User::where('email_address', $request->email_address)->first();

        // 3️⃣ Generate a strong random password
        $newPassword = $this->generateStrongPassword(10);

        // 4️⃣ Update the user's password (hashed)
        $user->password = Hash::make($newPassword);
        $user->save();

        // 5️⃣ Send the email with the new password
        Mail::to($user->email_address)->send(new NewPasswordMail(
            $user->first_name,    // Arg2: first name
            $user->email_address, // Arg1: email
            $newPassword          // Arg3: new password
        ));

        // 6️⃣ Log the activity
        Logs::create([
            'module'      => 'Users',
            'activity'    => "Password reset for user {$user->first_name} {$user->last_name}",
            'ip_address'  => $request->ip(),
            'created_by'  => Auth::id() ?? null, // null if system triggered
            'updated_by'  => Auth::id() ?? null,
            'create_time' => now(),
            'update_time' => now(),
        ]);

        // 7️⃣ Return back with a success message
       return redirect('/login')->with('status', 'A new password has been sent to your email.');

    }

    // 🔹 Strong password generator
    private function generateStrongPassword($length = 10)
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%&*_';

        $all = $upper . $lower . $numbers . $special;

        // Ensure each type is included at least once
        $password = [];
        $password[] = $upper[random_int(0, strlen($upper) - 1)];
        $password[] = $lower[random_int(0, strlen($lower) - 1)];
        $password[] = $numbers[random_int(0, strlen($numbers) - 1)];
        $password[] = $special[random_int(0, strlen($special) - 1)];

        // Fill remaining length
        for ($i = 4; $i < $length; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($password);

        return implode('', $password);
    }
}
