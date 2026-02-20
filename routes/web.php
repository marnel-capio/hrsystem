<?php
 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ActionBatchController;
 
/* ----------------------------- HOME & DASHBOARD ----------------------------- */
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');
 
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 

// List page
Route::get('/action/batches/list', [ActionBatchController::class, 'index'])
    ->name('action.list');
 
// Create page
Route::get('/action/batches/create', fn () => Inertia::render('action/batches/ActionBatchRegister'))
    ->name('action.create');
 
// Detail page
Route::get('/action/batches/{id}', [ActionBatchController::class, 'show'])
    ->name('action.show');
 
// Update (from modal edit)
Route::put('/action/batches/{id}', [ActionBatchController::class, 'update'])
    ->name('action.update');
 
require __DIR__.'/settings.php';
 