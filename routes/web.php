
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

    // LIST PAGE FOR SCHEDULES
    Route::get('action/schedules', [ResourceScheduleController::class, 'index'])
        ->name('action.schedules.index');

    // ACTION
    Route::get('/action', fn () => Inertia::render('action/Action'))->name('action.index');
    Route::get('/action/applications', fn () => Inertia::render('action/Applications'))->name('action.applications');
    Route::get('/action/batches', action: [ActionBatchController::class, 'index'])->name('action.list');    
    Route::get('/action/batches/create', [ActionBatchController::class, 'index'])->name('action.create');    

    Route::get('/action/batches', [ActionBatchController::class, 'index'])
    ->middleware(['auth'])
    ->name('action.list');
});

// Include other routes
require __DIR__.'/settings.php';

