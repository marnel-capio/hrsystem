<?php

use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Login POST
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

// ------------------------
// Authenticated Routes
// ------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | User Management (Permission 1 & 2 ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['check.permission'])->group(function () {

        Route::get('/user', fn () => Inertia::render('User/Index'))->name('user.index');
        Route::get('/user/register', fn () => Inertia::render('User/Register'))->name('user.register');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    });
    
});

require __DIR__.'/settings.php';
