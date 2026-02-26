<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceScheduleController;
use App\Http\Controllers\ActionBatchController;

/**
 * Web Routes
 */

// HOME
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

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

    // LIST PAGE FOR SCHEDULES - Permission check
    Route::get('action/schedules', function () {
        $user = auth()->user();
        
        if (!in_array((int)$user->permissions, [1, 2, 3])) {
            // Redirect with error as query parameter
            return redirect('/?error=' . urlencode('Access denied: You are not authorized to view this page.'));
        }

        return app(ResourceScheduleController::class)->index();
    })->name('action.schedules.index');


    // CREATE PAGE FOR SCHEDULES - Permission check
    Route::get('action/schedules/create', function () {
        $user = auth()->user();
        
        if (!in_array((int)$user->permissions, [1, 2])) {
            // Redirect with error as query parameter
            return redirect('/?error=' . urlencode('Access denied: You are not authorized to view this page.'));
        }

        return app(ResourceScheduleController::class)->create();
    })->name('action.schedules.create');

});

// Include other routes
require __DIR__.'/settings.php';