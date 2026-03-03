<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActionBatchModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
 
class ActionBatchController extends Controller
{
    // Show Register Page
    public function index(Request $request)
{
    $user = Auth::user();

    // Permission Check
    if (!in_array($user->permissions, [1, 2, 3])) {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Access denied: You are not authorized to view this page.');
    }

    $search = $request->input('search');

    $batches = ActionBatchModel::getPaginated($search, perPage: 20);
    $batchesTotal = ActionBatchModel::count();

    return Inertia::render('action/batches/ActionBatchList', [
        'batches' => $batches,
        'filters' => [
            'search' => $search,
        ],
        'batches_total' => $batchesTotal,
        'user_permissions' => $user->permissions,
    ]);
}

//For testing purposes:
   public function create()
{
    return Inertia::render('action/batches/ActionBatchRegister');
}
public function show($id)
    {
        $batch = ActionBatchModel::findOrFail($id);
        return Inertia::render('action/batches/ActionBatchDetail', ['batch'=> $batch]);
    }
}