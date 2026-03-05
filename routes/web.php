
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
   
    // Action Batches Routes
    Route::get('/action/batches', [ActionBatchController::class, 'index'])->name('action.batches.list')
        ->middleware(['auth'])
        ->name('action.batches.list');;    

    // For creating action batch
    Route::get('/action/batches/register', [ActionBatchController::class, 'create'])
        ->middleware(['auth'])
        ->name('action.batches.create');
    
    // Store action batch
    Route::post('/action/batches/store', [ActionBatchController::class, 'store'])->name('action.batches.store');

    Route::get('/action/batches/{id}', [ActionBatchController::class, 'show'])
        ->middleware(['auth'])
        ->name('action.batches.show');

    Route::get('/action/batches/{id}/edit', [ActionBatchController::class, 'edit'])
        ->middleware(['auth'])
        ->name('action.batches.edit');

    Route::post('/action/batches/{id}/update', [ActionBatchController::class, 'update'])
        ->middleware(['auth'])
        ->name('action.batches.update');
});

require __DIR__.'/settings.php';