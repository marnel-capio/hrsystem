<?php

use App\Http\Controllers\ActionApplicantController;
use App\Http\Controllers\ActionApplicantProgrammingLanguageController;
use App\Http\Controllers\ActionApplicantSkillController;
use App\Http\Controllers\ActionApplicationController;
use App\Http\Controllers\ActionBatchController;
use App\Http\Controllers\ApplicationImportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\IntermediateApplicationController;
use App\Http\Controllers\IntermediateInterviewerController;
use App\Http\Controllers\IntermediateProjectController;
use App\Http\Controllers\IntermediateRequisitionController;
use App\Http\Controllers\ResourceScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

    // action-applicants proglang list
    Route::get('/action/applicants/{applicantId}/languages', [ActionApplicantProgrammingLanguageController::class, 'index']);

    // action-applicants proglang add api
    Route::post('/action/applicants/{applicantId}/languages', [ActionApplicantProgrammingLanguageController::class, 'store']);

    // action-applicants proglang edit api
    Route::put('/action/applicants/{applicantId}/languages/{langId}', [ActionApplicantProgrammingLanguageController::class, 'update']);

    // action-applicants proglang delete single api
    Route::delete('/action/applicants/{applicantId}/languages/{langId}', [ActionApplicantProgrammingLanguageController::class, 'destroy']);

    // action-applicants proglang delete bulk api
    Route::post('/action/applicants/{applicantId}/languages/bulk-delete', [ActionApplicantProgrammingLanguageController::class, 'bulkDelete']);

    Route::prefix('action/applicants/{applicantId}/skills')->group(function () {
        Route::get('/', [ActionApplicantSkillController::class, 'index']);
        Route::post('/', [ActionApplicantSkillController::class, 'store']);
        Route::put('/{skillId}', [ActionApplicantSkillController::class, 'update']);
        Route::delete('/{skillId}', [ActionApplicantSkillController::class, 'destroy']);
        Route::post('/bulk-delete', [ActionApplicantSkillController::class, 'bulkDelete']);
    });

    Route::post('/intermediate/applications/{id}/update-paper-screening', [IntermediateApplicationController::class, 'updatePaperScreening'])
        ->name('intermediate.applications.update-paper-screening');

    Route::middleware(['auth'])->group(function () {
        // Interviewer routes
        Route::get('/intermediate/applications/{applicationId}/interviews', [IntermediateInterviewerController::class, 'index']);
        Route::get('/intermediate/interviewers/available', [IntermediateInterviewerController::class, 'available']);
        Route::post('/intermediate/applications/{applicationId}/interviews/bulk-add', [IntermediateInterviewerController::class, 'bulkAdd']);
        Route::post('/intermediate/applications/{applicationId}/interviews/bulk-update-schedule', [IntermediateInterviewerController::class, 'bulkUpdateSchedule']);
        Route::post('/intermediate/applications/{applicationId}/interviews/{interviewId}/decision', [IntermediateInterviewerController::class, 'decision']);
        Route::post('/intermediate/applications/{applicationId}/send-notification', [IntermediateInterviewerController::class, 'sendNotification']);
        Route::get('/intermediate/applications/{applicationId}/initial-assignments', [IntermediateInterviewerController::class, 'initialAssignments']);
        Route::get('/intermediate/applications/{applicationId}/final-assignments', [IntermediateInterviewerController::class, 'finalAssignments']);
        Route::get('/intermediate/applications/{applicationId}/has-mixed-results/{type}', [IntermediateInterviewerController::class, 'hasMixedResults']);
    });

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

Route::middleware(['auth', 'check.permission'])->group(function () {
    Route::get('/action/schedules', [ResourceScheduleController::class, 'index'])->name('action.schedules.index');
    Route::get('/action/schedules/register', [ResourceScheduleController::class, 'create'])->name('action.schedules.register');
    Route::post('/action/schedules', [ResourceScheduleController::class, 'store'])->name('action.schedules.store');
    Route::get('/action/schedules/{id}', [ResourceScheduleController::class, 'show'])->name('action.schedules.show');
    Route::get('/action/schedules/{id}/edit', [ResourceScheduleController::class, 'edit'])->name('action.schedules.edit');
    Route::put('/action/schedules/{id}/update', [ResourceScheduleController::class, 'update'])->name('action.schedules.update');
    Route::post('/action/schedules/{id}/send-notification', [ResourceScheduleController::class, 'sendResourceScheduleNotification'])->name('action.schedules.notify');
    Route::delete('/action/schedules/{id}', [ResourceScheduleController::class, 'destroy'])->name('action.schedules.destroy');
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

        Route::get('/batches/{batchId}/resource-schedule', [ActionApplicationController::class, 'getBatchResourceSchedule'])
            ->name('batch-resource-schedule');

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
    // action-applicants list
    Route::get('/action/applicants', [ActionApplicantController::class, 'index'])
        ->name('action.applicants.index');

    // action-applicants register
    Route::get('/action/applicants/register', [ActionApplicantController::class, 'create'])
        ->name('action.applicants.register');

    // action-applicants register api
    Route::post('/action/applicants', [ActionApplicantController::class, 'store'])
        ->name('action.applicants.store');

    // action-applicants email checker api
    Route::post('/action/applicants/check-email', [ActionApplicantController::class, 'checkEmail'])
        ->name('action.applicants.check-email');

    // action-applicants detail
    Route::get('/action/applicants/{id}', [ActionApplicantController::class, 'show'])
        ->name('action.applicants.detail');

    // action-applicants edit
    Route::get('/action/applicants/{id}/edit', [ActionApplicantController::class, 'edit'])
        ->name('action.applicants.edit');

    // action-applicants update api
    Route::put('/action/applicants/{id}/update', [ActionApplicantController::class, 'update'])
        ->name('action.applicants.update');

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
// Intermediate Resource Requisitions
// ------------------------
Route::middleware(['auth', 'check.permission'])->group(function () {
    Route::get('/intermediate/resource-requisitions', [IntermediateRequisitionController::class, 'index'])->name('intermediate.requisitions.index');
    Route::get('/intermediate/resource-requisitions/register', [IntermediateRequisitionController::class, 'create'])->name('intermediate.requisitions.register');
    Route::post('/intermediate/resource-requisitions', [IntermediateRequisitionController::class, 'store'])->name('intermediate.requisitions.store');
    Route::get('/intermediate/resource-requisitions/{id}', [IntermediateRequisitionController::class, 'show'])->name('intermediate.requisitions.show');
    Route::get('/intermediate/resource-requisitions/{id}/edit', [IntermediateRequisitionController::class, 'edit'])->name('intermediate.requisitions.edit');
    Route::post('/intermediate/resource-requisitions/{id}/update', [IntermediateRequisitionController::class, 'update'])->name('intermediate.requisitions.update');
});

Route::middleware(['check.permission'])->group(function () {
    Route::get('/intermediate/applications', [IntermediateApplicationController::class, 'index'])
        ->name('intermediate.applications.index');

    // Import intermediate applicants (matches your Vue router.post)
    Route::post('/intermediate/applications/import', [ApplicationImportController::class, 'importIntermediateApplicants'])
        ->name('intermediate.applications.import');

    Route::get('/intermediate/applications/register', [IntermediateApplicationController::class, 'create'])
        ->name('intermediate.applications.register');

    Route::post('/intermediate/applications', [IntermediateApplicationController::class, 'store'])
        ->name('intermediate.applications.store');

    Route::get('/intermediate/applications/{id}', [IntermediateApplicationController::class, 'show'])
        ->name('intermediate.applications.show');
});

// ------------------------
// Include additional routes
// ------------------------
require __DIR__.'/settings.php';
