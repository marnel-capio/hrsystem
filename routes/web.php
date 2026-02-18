<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AuthController;

// Home / HR Dashboard
Route::get('/', function () {
    return Inertia::render('HRDashboard'); // resources/js/pages/HRDashboard.vue
})->middleware('auth')->name('dashboard'); // protect with auth

// Login page
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

// Login POST
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Include other routes
require __DIR__.'/settings.php';