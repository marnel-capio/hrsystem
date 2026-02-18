<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Settings\ResourceScheduleController;

/**
 * Web Routes
 *
 * This file defines the web routes for the application. Routes are loaded by the RouteServiceProvider
 * within a group that contains the "web" middleware group. These routes handle web-based requests
 * and render Inertia.js pages for the frontend.
 */

// Home route: Displays the welcome page with registration availability check
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Dashboard route: Displays the authenticated user's dashboard, requires authentication and email verification
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Resource routes for managing resource schedules (CRUD operations)
// This generates standard RESTful routes (index, create, store, show, edit, update, destroy)
// for the 'action/schedules' prefix, handled by ResourceScheduleController
Route::resource('action/schedules', ResourceScheduleController::class)->names([
    'index' => 'action.schedules.index',
    'create' => 'action.schedules.create',
    'store' => 'action.schedules.store',
    'show' => 'action.schedules.show',
    'edit' => 'action.schedules.edit',
    'update' => 'action.schedules.update',
    'destroy' => 'action.schedules.destroy',
]);

// Include additional settings routes from a separate file for better organization
require __DIR__.'/settings.php';