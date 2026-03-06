<?php

use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResourceScheduleController;
use App\Http\Controllers\UserController;
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

    // Update user
        Route::put('/user/{id}/update', [UserController::class, 'update'])
            ->name('user.update');    
            
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

        // Show Edit User Details
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])
            ->name('user.edit');

        
    });

    
    // ------------------------
    // Action Schedules
    // ------------------------
    Route::prefix('action/schedules')->group(function () {
        Route::get('/', function () {
            $user = auth()->user();

            if (! in_array((int) $user->permissions, [1, 2, 3])) {
                return redirect()->route('dashboard')
                    ->with('error', 'Access denied: You are not authorized to view this page.');
            }

            return app(ResourceScheduleController::class)->index();
        })->name('action.schedules.index');
    });

    // ------------------------
    // Actions
    // ------------------------
    Route::prefix('action')->group(function () {

        Route::get('/', fn () => Inertia::render('action/Action'))
            ->name('action.index');

        Route::get('/applications', fn () => Inertia::render('action/Applications'))
            ->name('action.applications');

        // Batches
        Route::get('/batches', [ActionBatchController::class, 'index'])
            ->name('action.list');

        Route::get('/batches/register', [ActionBatchController::class, 'create'])
            ->name('action.create');

        Route::get('/batches/{id}', [ActionBatchController::class, 'show'])
            ->name('action.show');
    });
});

// ------------------------
// Include additional routes
// ------------------------
require __DIR__.'/settings.php';
