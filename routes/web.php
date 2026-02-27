<?php
 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ActionBatchController;
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
 
// HOME
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');
 
 
// DASHBOARD
Route::get('/dashboard', function () {
    return Inertia::render('HRDashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 
 
// ACTION BATCH LIST
Route::get('action/batches', function () {
 
    $user = auth()->user();
 
    if (
        !$user ||
        !in_array($user->position, ['Admin', 'HR Manager', 'HR Recruiter']) ||
        $user->active_status != 1
    ) {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Access denied: You are not authorized to view this page.');
    }
 
    return app(ActionBatchController::class)->index(request());
 
})->middleware(['auth'])->name('action.batches.list');
 
 
// ACTION BATCH CREATE (ONLY Admin & HR Manager)
Route::get('action/batches', function () {
 
    $user = auth()->user();
 
    if (
        !$user ||
        !in_array($user->position, ['Admin', 'HR Manager', 'HR Recruiter']) ||
        $user->active_status != 1
    ) {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Access denied: You are not authorized to view this page.');
    }
 
    return app(App\Http\Controllers\ActionBatchController::class)->index(request());
 
})->middleware(['auth']);