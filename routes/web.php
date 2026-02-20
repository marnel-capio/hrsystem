<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Settings\ResourceScheduleController;

/**
 * Public Routes
 */

// Login page
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

// Login POST
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/**
 * Protected Routes (authenticated users only)
 */
Route::middleware(['web', 'auth'])->group(function() {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ===============================
    // RESOURCE SCHEDULES (Edit/Details)
    // ===============================

    // Show details for a single schedule
    Route::get('action/schedules/{id}', [ResourceScheduleController::class, 'show'])
        ->name('action.schedules.show');

    // Edit a schedule (edit page)
    Route::get('action/schedules/{id}/edit', [ResourceScheduleController::class, 'edit'])
        ->name('action.schedules.edit');

    // Update a schedule (submit edits)
    Route::put('action/schedules/{id}', [ResourceScheduleController::class, 'update'])
        ->name('action.schedules.update');
});

// Include other settings routes
require __DIR__.'/settings.php';