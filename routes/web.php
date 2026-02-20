<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceScheduleController;
use Inertia\Inertia;
use Laravel\Fortify\Features;
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

/**
 * Protected Routes
 */
Route::middleware(['web', 'auth'])->group(function() {

    // DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    // LIST PAGE FOR SCHEDULES
    Route::get('action/schedules', [ResourceScheduleController::class, 'index'])
        ->name('action.schedules.index');

    // Show the register (create) page
    Route::get('action/schedules/create', [ResourceScheduleController::class, 'create'])
        ->name('action.schedules.create');

    // Handle form submission (store)
    Route::post('action/schedules', [ResourceScheduleController::class, 'store'])
        ->name('action.schedules.store');

    // Show details page for a single schedule
    Route::get('action/schedules/{id}', [ResourceScheduleController::class, 'show'])
        ->name('action.schedules.show');
});

// Include other routes
require __DIR__.'/settings.php';
