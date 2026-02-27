
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use Laravel\Fortify\Features;
use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\UserController;


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
    
    // User
    Route::get('/user', fn () => Inertia::render('User/Index'))->name('user.index');
    Route::get('/user/register', fn () => Inertia::render('User/Register'))->name('user.register');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    // Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');

    // Action 
    Route::get('/action', fn () => Inertia::render('action/Action'))->name('action.index');
    Route::get('/action/applications', fn () => Inertia::render('action/Applications'))->name('action.applications');
    Route::get('/action/batches', action: [ActionBatchController::class, 'index'])->name('action.list');    
    Route::get('/action/batches/create', [ActionBatchController::class, 'index'])->name('action.create');    

    Route::get('/action/batches', [ActionBatchController::class, 'index'])
    ->middleware(['auth'])
    ->name('action.list');
});


require __DIR__.'/settings.php';
