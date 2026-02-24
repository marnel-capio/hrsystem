<?php
 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ActionBatchController;
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
 
// Home / Welcome page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');
 
// Dashboard - requires authentication
Route::get('/dashboard', function () {
    return Inertia::render('HRDashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 
// ACTION main page
Route::get('/action', fn () => Inertia::render('action/Action'))
    ->middleware(['auth'])
    ->name('action.index');
 
// ACTION BATCHES
// Show register page (controller handles authorization and redirect)
Route::get('/action/batches/create', [ActionBatchController::class, 'index'])
    ->middleware(['auth'])
    ->name('action.create');

// Store action batch
Route::post('/action/batches/store', [ActionBatchController::class, 'store'])
    ->middleware(['auth'])
    ->name('action.store');
 
// Show details page
Route::get('/action/batches/{id}', [ActionBatchController::class, 'detail'])
    ->middleware(['auth'])
    ->name('action.detail');
    
 
// Include additional settings routes if needed
require __DIR__ . '/settings.php';
 