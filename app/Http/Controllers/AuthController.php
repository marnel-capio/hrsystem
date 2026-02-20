<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return Inertia::render('auth/Login'); // resources/js/pages/auth/Login.vue
    }

    // Handle login submission
    public function login(LoginRequest $request)
    {
        // Validate
        $credentials = $request->validate([
            'email_address' => ['required', 'email'],
            'password'      => ['required'],
        ]);

        // Attempt login
        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email_address' => 'Invalid credentials.',
            ]);
        }
        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        \Log::debug($user);
        Auth::login($user);

        // Optional: log login
        // Log::create([...]);

        // ✅ Redirect to HRDashboard.vue
        return redirect()->intended();
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
