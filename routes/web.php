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
 
// Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('HRDashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 
// ACTION BATCHES
 
Route::get('/action/batches/create', [ActionBatchController::class, 'index'])
    ->middleware(['auth'])
    ->name('action.create');
 
Route::post('/action/batches/store', [ActionBatchController::class, 'store'])
    ->middleware(['auth'])
    ->name('action.store');
 
require __DIR__ . '/settings.php';