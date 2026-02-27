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
 
// Home
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');
 
 
// Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('HRDashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 
 
// ACTION BATCH LIST (with permission check in route)
Route::get('action/batches', function () {
 
    $user = auth()->user();
 
    if (
        !$user ||
        !in_array($user->position, ['Admin', 'HR Manager', 'HR Recruiter']) ||
        $user->active_status != 1
    ) {
        return Inertia::location(
            route('dashboard') . '?error=Access%20denied:%20You%20are%20not%20authorized%20to%20view%20this%20page.'
        );
    }
 
    return app(ActionBatchController::class)->index(request());
 
})->middleware(['auth'])->name('action.batches.list');
 
 
// ACTION BATCH CREATE (ONLY Admin & HR Manager)
Route::get('action/batches/create', function () {
 
    $user = auth()->user();
 
    if (
        !$user ||
        !in_array($user->position, ['Admin', 'HR Manager']) ||
        $user->active_status != 1
    ) {
        return Inertia::location(
            route('dashboard') . '?error=Access%20denied:%20You%20are%20not%20authorized%20to%20access%20this%20page.'
        );
    }
 
    return app(ActionBatchController::class)->create();
 
})->middleware(['auth'])->name('action.batches.create');
 
 
require __DIR__.'/settings.php';
 