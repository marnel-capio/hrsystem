<?php

use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\ActionApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\IntermediateProjectController;
use App\Http\Controllers\ResourceScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActionApplicationController;
use App\Http\Controllers\ApplicationImportController;
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
        // ACTION APPLICATIONS
        // ------------------------
        Route::middleware(['auth', 'check.permission'])->group(function () {
Route::prefix('action/applications')->name('action.applications.')->group(function () {
    Route::get('/', [ActionApplicationController::class, 'index'])->name('index');
    Route::get('/register', [ActionApplicationController::class, 'create'])->name('create');
    Route::post('/', [ActionApplicationController::class, 'store'])->name('store');
    Route::get('/{id}', [ActionApplicationController::class, 'show'])->name('show');
    Route::post('/import', [ApplicationImportController::class, 'import'])->name('import');
    Route::get('/{id}/edit', [ActionApplicationController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ActionApplicationController::class, 'update'])->name('update');

    Route::get('/eligible-applicants/{batchId}', [ActionApplicationController::class, 'getApplicantsForBatch'])
        ->name('eligible-applicants');
    Route::post('/check-eligibility', [ActionApplicationController::class, 'checkEligibility'])
        ->name('check-eligibility');

    Route::post('/{id}/interviews/bulk-add', [ActionApplicationController::class, 'bulkAddInterviews'])
        ->name('interviews.bulk-add');

    Route::post('/{applicationId}/interviews/bulk-delete', [ActionApplicationController::class, 'bulkDeleteInterviews'])
        ->name('interviews.bulk-delete');

    Route::post('/{applicationId}/interviews/{interviewId}/decision', [ActionApplicationController::class, 'submitInterviewDecision'])
        ->name('interviews.decision');

    Route::post(
        '/{applicationId}/interviews/bulk-update-schedule',
        [ActionApplicationController::class, 'bulkUpdateInterviewSchedule']
    )->name('interviews.bulk-update-schedule');

    Route::post(
        '/{application}/send-notification',
        [ActionApplicationController::class, 'sendNotification']
    )->name('send-notification');

    Route::get('/{id}/print', [ActionApplicationController::class, 'print'])
        ->name('print');

});
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

        Route::get('/action/applicants/{id}', [ActionApplicantController::class, 'show'])
                ->name('action.applicants.detail');
    });



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
