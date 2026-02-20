<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceScheduleController;

// Show the register page
Route::get('action/schedules/create', [ResourceScheduleController::class, 'create'])
    ->name('action.schedules.create');

// Handle form submission
Route::post('action/schedules', [ResourceScheduleController::class, 'store'])
    ->name('action.schedules.store')
    ->middleware(['auth', 'verified']);

// Show details page for a schedule
Route::get('action/schedules/{id}', [ResourceScheduleController::class, 'show'])
    ->name('action.schedules.show')
    ->middleware(['auth', 'verified']);