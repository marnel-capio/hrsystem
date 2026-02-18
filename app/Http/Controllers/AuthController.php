<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

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
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email_address' => 'Invalid credentials.',
            ]);
        }
        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Log::debug($user);
        Auth::login($user);
        $request->session()->regenerate();

        // Log the activity
        $user = Auth::user();
        Logs::create([
            'module'     => 'Users',
            'activity'   => "{$user->first_name} {$user->last_name} logged in successfully",
            'ip_address' => $request->ip(),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'create_time'=> now(),
            'update_time'=> now(),
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
