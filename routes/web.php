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

    // DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // LIST PAGE FOR SCHEDULES - Permission check in route
    Route::get('action/schedules', function () {
        $user = auth()->user();
        
        if (!in_array((int)$user->permissions, [1, 2, 3])) {
            // Use Inertia redirect with error in query string
            return redirect()
    ->route('dashboard')
    ->with('error', 'Access denied: You are not authorized to view this page.');
        }

        return app(ResourceScheduleController::class)->index();
    })->name('action.schedules.index');

<<<<<<< HEAD
    Route::get('/action/batches/{id}', [ActionBatchController::class, 'show']);
=======

    // CREATE PAGE FOR SCHEDULES - Permission check in route
    // Route::get('action/schedules/register', function () {
    //     $user = auth()->user();
        
    //     if (!in_array((int)$user->permissions, [1, 2])) {
    //         // Use Inertia redirect with error in query string
    //         return Inertia::location(route('dashboard') . '?error=Access%20denied:%20You%20are%20not%20authorized%20to%20view%20this%20page.');
    //     }

    //     return app(ResourceScheduleController::class)->create();
    // })->name('action.schedules.register');


// Include other routes
   
    // Action
    Route::get('/action', fn () => Inertia::render('action/Action'))->name('action.index');
    Route::get('/action/applications', fn () => Inertia::render('action/Applications'))->name('action.applications');
    Route::get('/action/batches', action: [ActionBatchController::class, 'index'])->name('action.list');    
    Route::get('/action/batches', [ActionBatchController::class, 'index'])
    ->middleware(['auth'])
    ->name('action.list');
>>>>>>> origin/develop

    //For Testing purposes
    Route::get('/action/batches/register', [ActionBatchController::class, 'create'])
    ->middleware(['auth'])
    ->name('action.create');
    Route::get('/action/batches/{id}', [ActionBatchController::class, 'show']);

});

// Include other routes
 
 
require __DIR__.'/settings.php';
 