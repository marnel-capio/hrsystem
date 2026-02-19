<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            'password' => ['required'],
        ]);

        // Attempt login
        if (! Auth::attempt($credentials)) {

            //  Log failed login attempt
            Logs::create([
                'module' => 'Users',
                'activity' => "A person using this email address {$request->email_address} failed to log in.",
                'ip_address' => $request->ip(),
                'created_by' => null,
                'updated_by' => null,
                'create_time' => now(),
                'update_time' => now(),
            ]);

            return back()->withErrors([
                'email_address' => 'Invalid credentials.',
            ]);
        }

        // Active user checker
        $user = Auth::user();

        if ($user->active_status != 1) {
            Auth::logout();

            Logs::create([
                'module' => 'Users',
                'activity' => "A person using this email address {$request->email_address} failed to log in.",
                'ip_address' => $request->ip(),
                'created_by' => null,
                'updated_by' => null,
                'create_time' => now(),
                'update_time' => now(),
            ]);

            return back()->withErrors([
                'email_address' => 'Your account is no longer active. Please check it with your manager or admin.',
            ]);
        }

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Log::debug($user);
        Auth::login($user);
        $request->session()->regenerate();

        // Log the activity
        $user = Auth::user();
        Logs::create([
            'module' => 'Users',
            'activity' => "{$user->first_name} {$user->last_name} logged in successfully",
            'ip_address' => $request->ip(),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'create_time' => now(),
            'update_time' => now(),
        ]);

        // ✅ Redirect to HRDashboard.vue
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
