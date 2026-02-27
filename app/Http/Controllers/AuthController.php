<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Rules\AWSEmailAddress;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return Inertia::render('auth/Login');
    }

    // Handle login submission
    public function login(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email_address' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login
        if (! Auth::attempt($credentials)) {

            // Log failed login
            Log::createLog(
                'Users',
                "A person using this email address {$request->email_address} failed to log in.",
                null
            );

            return back()->withErrors([
                'email_address' => 'Invalid credentials.',
            ]);
        }

        // Regenerate session
        $request->session()->regenerate();

        // Log successful login
        $user = Auth::user();

        Log::createLog(
            'Users',
            "{$user->first_name} {$user->last_name} logged in successfully",
            $user->id
        );

        // Redirect to dashboard
        return redirect()->route('dashboard');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
