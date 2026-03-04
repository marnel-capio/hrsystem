<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceScheduleController;
use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\ForgotPasswordController;

/**
 * Web Routes
 */

// HOME (public welcome page)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
});

// ------------------------
// Authenticated Routes
// ------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Action routes
    Route::get('/action', fn () => Inertia::render('action/Action'))->name('action.index');
    Route::get('/action/applications', fn () => Inertia::render('action/Applications'))->name('action.applications');
    Route::get('/action/batches', [ActionBatchController::class, 'index'])->name('action.list');
    Route::get('/action/batches/register', [ActionBatchController::class, 'create'])->name('action.create');
    Route::get('/action/batches/{id}', [ActionBatchController::class, 'show']);

    // Resource Schedule routes
    Route::get('/action/schedules', [ResourceScheduleController::class, 'index'])->name('action.schedules.index');
    Route::get('/action/schedules/register', [ResourceScheduleController::class, 'create'])->name('action.schedules.register');
    Route::post('/action/schedules', [ResourceScheduleController::class, 'store'])->name('action.schedules.store');
    Route::get('/action/schedules/{id}', [ResourceScheduleController::class, 'show'])->name('action.schedules.show');

});

// Include additional route files
require __DIR__.'/settings.php';