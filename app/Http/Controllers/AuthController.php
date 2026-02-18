<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
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
    public function login(Request $request)
    {
        // Validate
        $credentials = $request->validate([
            'email_address' => ['required', 'email'],
            'password'      => ['required'],
        ]);

        // Attempt login
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email_address' => 'Invalid credentials.',
            ]);
        }

        // Regenerate session
        $request->session()->regenerate();

        // Optional: log login
        // Log::create([...]);

        // ✅ Redirect to HRDashboard.vue
        return redirect('/'); // your dashboard route
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
