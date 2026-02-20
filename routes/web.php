<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\ResourceScheduleController;

// Show the register page
Route::get('action/schedules/register', [ResourceScheduleController::class, 'create'])
    ->name('action.schedules.create')
    ->middleware(['auth', 'verified']);

// Handle form submission
Route::post('action/schedules', [ResourceScheduleController::class, 'store'])
    ->name('action.schedules.store')
    ->middleware(['auth', 'verified']);