<?php

use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\ActionApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\IntermediateProjectController;
use App\Http\Controllers\ResourceScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * Web Routes
 */

// ------------------------
// Guest Routes
// ------------------------

// Login POST
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->name('password.email');

// ------------------------
// Authenticated Routes
// ------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

        Route::get('/action/applicants/{id}', [ActionApplicantController::class, 'show'])
                ->name('action.applicants.detail');

});

    // Update user
        Route::put('/user/{id}/update', [UserController::class, 'update'])
            ->name('user.update');    
            
    // ------------------------
    // User Management (Permissions 1 & 2 Only)
    // ------------------------
    Route::middleware(['check.permission'])->group(function () {

        // Users list
        Route::get('/user', [UserController::class, 'index'])
            ->name('user.index');

        // Register new user page
        Route::get('/user/register', [UserController::class, 'create'])
            ->name('user.register');

        // Store new user
        Route::post('/user', [UserController::class, 'store'])
            ->name('user.store');

        // Show user detail
        Route::get('/user/{id}', [UserController::class, 'show'])
            ->name('user.show');

        // Show Edit User Details
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])
            ->name('user.edit');

        
    });

    
    // ------------------------
    // Resource Schedules
    // ------------------------
    
    Route::middleware(['check.permission'])->group(function () {
        Route::get('/action/schedules', [ResourceScheduleController::class, 'index'])->name('action.schedules.index');
        Route::get('/action/schedules/register', [ResourceScheduleController::class, 'create'])->name('action.schedules.register');
        Route::post('/action/schedules', [ResourceScheduleController::class, 'store'])->name('action.schedules.store');
        Route::get('/action/schedules/{id}', [ResourceScheduleController::class, 'show'])->name('action.schedules.show');
        Route::get('/action/schedules/{id}/edit', [ResourceScheduleController::class, 'edit'])->name('action.schedules.edit');
        Route::put('/action/schedules/{id}/update', [ResourceScheduleController::class, 'update'])->name('action.schedules.update');
        //email
        Route::post('/action/schedules/{id}/send-notification', 
            [ResourceScheduleController::class, 'sendResourceScheduleNotification']
        )->name('action.schedules.notify');
        //delete
        Route::delete('/action/schedules/{id}', [ResourceScheduleController::class, 'destroy'])
        ->name('action.schedules.destroy');
        });            

    // ------------------------
    // ACTION Batches
    // ------------------------
    Route::middleware(['auth', 'check.permission'])->group(function () {
        Route::get('/action/batches', [ActionBatchController::class, 'index'])->name('action.batches.list');
        Route::get('/action/batches/register', [ActionBatchController::class, 'create'])->name('action.batches.register');
        Route::post('/action/batches', [ActionBatchController::class, 'store'])->name('action.batches.store');
        Route::get('/action/batches/{id}', [ActionBatchController::class, 'show'])->name('action.batches.show');       
        Route::get('/action/batches/{id}/edit', [ActionBatchController::class, 'edit'])->name('action.batches.edit');        
        Route::post('/action/batches/{id}/update', [ActionBatchController::class, 'update'])->name('action.batches.update');
    });

    // ------------------------
    // ACTION Applicants
    // ------------------------
    Route::middleware(['check.permission'])->group(function () {
        //action-applicants list
        Route::get('/action/applicants', [ActionApplicantController::class, 'index'])
                ->name('action.applicants.index');
        
        //action-applicants register       
        Route::get('/action/applicants/register', [ActionApplicantController::class, 'create'])
                ->name('action.applicants.register');

        Route::post('/action/applicants', [ActionApplicantController::class, 'store'])
                ->name('action.applicants.store');

        Route::post('/action/applicants/check-email', [ActionApplicantController::class, 'checkEmail'])
                ->name('action.applicants.check-email');

        
    });


    // ------------------------
    // Intermediate Projects
    // ------------------------
    Route::middleware(['auth', 'check.permission'])->group(function () {
        Route::get('/intermediate/projects', [IntermediateProjectController::class, 'index'])->name('intermediate.projects.list');
        Route::get('/intermediate/projects/register', [IntermediateProjectController::class, 'create'])->name('intermediate.projects.register');
        Route::post('/intermediate/projects', [IntermediateProjectController::class, 'store'])->name('intermediate.projects.store');
        Route::get('/intermediate/projects/{id}', [IntermediateProjectController::class, 'show'])->name('intermediate.projects.show');       
        Route::get('/intermediate/projects/{id}/edit', [IntermediateProjectController::class, 'edit'])->name('intermediate.projects.edit');        
        Route::post('/intermediate/projects/{id}/update', [IntermediateProjectController::class, 'update'])->name('intermediate.projects.update');
    });

// ------------------------
// Include additional routes
// ------------------------
require __DIR__.'/settings.php';