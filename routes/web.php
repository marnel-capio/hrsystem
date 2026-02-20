<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ResourceScheduleController;

/**
 * Web Routes
 */

// HOME
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Load additional settings routes
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;



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

// Protected routes
Route::middleware(['web', 'auth'])->group(function () {

    // DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // LIST PAGE FOR SCHEDULES
    Route::get('action/schedules', [ResourceScheduleController::class, 'index'])
        ->name('action.schedules.index');
});

// Include other routes
require __DIR__.'/settings.php';