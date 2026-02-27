
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
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
    
    // Action 
    Route::get('/action', fn () => Inertia::render('action/Action'))->name('action.index');
    Route::get('/action/applications', fn () => Inertia::render('action/Applications'))->name('action.applications');
    Route::get('/action/batches', action: [ActionBatchController::class, 'index'])->name('action.list');    
    Route::get('/action/batches/create', [ActionBatchController::class, 'index'])->name('action.create');    

    Route::get('/action/batches', [ActionBatchController::class, 'index'])
    ->middleware(['auth'])
    ->name('action.list');

    
    // Resource Schedules Routes
Route::get('/action/schedules', [ResourceScheduleController::class, 'index'])->name('action.schedules.index');
    
    // CREATE PAGE FOR SCHEDULES - Permission check in route
    Route::get('/action/schedules/create', function () {
        $user = auth()->user();
        
        if (!in_array((int)$user->permissions, [1, 2])) {
            return Inertia::location(route('dashboard') . '?error=Access%20denied:%20You%20are%20not%20authorized%20to%20view%20this%20page.');
        }

        return app(ResourceScheduleController::class)->create();
    })->name('action.schedules.create');

        // STORE SCHEDULE (POST)
        Route::post('/action/schedules', [ResourceScheduleController::class, 'store'])->name('action.schedules.store');

    // SHOW SCHEDULE DETAILS
    Route::get('/action/schedules/{id}', [ResourceScheduleController::class, 'show'])->name('action.schedules.show');

});


// Include other routes
require __DIR__.'/settings.php';