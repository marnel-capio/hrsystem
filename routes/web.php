<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use Laravel\Fortify\Features;
use App\Http\Controllers\ActionBatchController;

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
Route::middleware(['web', 'auth'])->group(function() {
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
    
    // Action Batches Routes
    Route::get('/action/batches', [ActionBatchController::class, 'index'])->name('action.batches.list');    

    // For creating action batch
    Route::get('/action/batches/register', [ActionBatchController::class, 'create'])
        ->middleware(['auth'])
        ->name('action.batches.create');
    
    Route::get('/action/batches/{id})', [ActionBatchController::class, 'detail'])
        ->middleware(['auth'])
        ->name('action.batches.detail');
    
    // Store action batch
    Route::post('/action/batches/store', [ActionBatchController::class, 'store'])->name('action.batches.store');

    Route::get('/action/batches/{id}', [ActionBatchController::class, 'show']);
});

require __DIR__.'/settings.php';