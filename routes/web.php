<?php

use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResourceScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActionApplicantController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * Web Routes
 */

// ------------------------
// Guest Routes
// ------------------------

// Login POST
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->name('password.email');

// ------------------------
// Authenticated Routes
// ------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    //action-applicants list
    Route::get('/action/applicants', [ActionApplicantController::class, 'index'])
            ->name('action.applicants.index');
    
    //action-applicants register       
    Route::get('/action/applicants/register', [ActionApplicantController::class, 'create'])
            ->name('action.applicants.register');
});

    // ------------------------
    // User Management (Permissions 1 & 2 Only)
    // ------------------------
    Route::middleware(['check.permission'])->group(function () {

        // Users list
        Route::get('/user', fn () => Inertia::render('user/Index'))
            ->name('user.index');

        // Register new user page
        Route::get('/user/register', [UserController::class, 'create'])
            ->name('user.register');

        // Store new user
        Route::post('/user', [UserController::class, 'store'])
            ->name('user.store');

        // Show user detail
        Route::get('/user/{id}', [UserController::class, 'show'])
            ->name('user.show');
    });

    // ------------------------
    // Resource Schedules
    // ------------------------
    
    Route::middleware(['check.permission'])->group(function () {
        Route::get('/action/schedules', [ResourceScheduleController::class, 'index'])->name('action.schedules.index');
        Route::get('/action/schedules/register', [ResourceScheduleController::class, 'create'])->name('action.schedules.register');
        Route::post('/action/schedules', [ResourceScheduleController::class, 'store'])->name('action.schedules.store');
        Route::get('/action/schedules/{id}', [ResourceScheduleController::class, 'show'])->name('action.schedules.show');
    });

    // ------------------------
    // Actions
    // ------------------------
    Route::middleware(['auth', 'check.permission'])->group(function () {
        Route::get('/action/batches', [ActionBatchController::class, 'index'])->name('action.batches.list');
        Route::get('/action/batches/register', [ActionBatchController::class, 'create'])->name('action.batches.register');
        Route::post('/action/batches', [ActionBatchController::class, 'store'])->name('action.batches.store');
        Route::get('/action/batches/{id}', [ActionBatchController::class, 'show'])->name('action.batches.show');
    });

// ------------------------
// Include additional routes
// ------------------------
require __DIR__.'/settings.php';