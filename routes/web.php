<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Settings\ResourceScheduleController;

/**
 * Web Routes
 */

// HOME
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// DASHBOARD
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===============================
// LIST PAGE FOR SCHEDULES
// ===============================
Route::get('action/schedules', [ResourceScheduleController::class, 'index'])
    ->name('action.schedules.index');

// Load additional settings routes
require __DIR__.'/settings.php';