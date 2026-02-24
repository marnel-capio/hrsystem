<?php
 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ActionBatchController;
 
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');
 
Route::get('dashboard', function () {
    return Inertia::render('HRDashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 
// ACTION
Route::get('/action', fn () => Inertia::render('action/Action'))
    ->middleware(['auth'])
    ->name('action.index');
 
Route::get('/action/batches', [ActionBatchController::class, 'index'])
    ->middleware(['auth'])
    ->name('action.list');
 
require __DIR__.'/settings.php';
 