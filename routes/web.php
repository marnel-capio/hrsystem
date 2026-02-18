<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;


// Login page
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

// Login POST
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

// ------------------------
// Authenticated Routes
// ------------------------
Route::middleware(['web', 'auth'])->group(function(){
    Route::get('/', [DashboardController::class, 'index']);

    // HR Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

});

// Include other routes
require __DIR__.'/settings.php';
