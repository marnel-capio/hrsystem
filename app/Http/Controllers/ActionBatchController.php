<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActionBatchModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
 
class ActionBatchController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();

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

   public function create()
{
    $user = Auth::user();
    if (!in_array($user->permissions, [1, 2])) {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Access denied: You are not authorized to view this page.');
    }
    return Inertia::render(component: 'action/batches/ActionBatchRegister');
}

public function store(Request $request)
{
    $user = Auth::user();

    // Validate the incoming request
    $validated = $request->validate([
        'action_batch' => 'required|string|max:20|unique:action_batches,action_batch',
        'target_trainees' => 'required|integer|max:99',
        'target_date' => 'required|string|max:10',
        'remarks' => 'nullable|string|max:1024',
    ], [
        'action_batch.unique' => 'Action Batch already exist.'
    ]);

    // Save to DB
    $batch = new ActionBatchModel();
    $batch->action_batch = strtoupper($validated['action_batch']); 
    $batch->target_trainees = $validated['target_trainees'];
    $batch->target_date = $validated['target_date'];
    $batch->remarks = $validated['remarks'] ?? null;
    $batch->created_by = $user->id;
    $batch->created_time = now();
    $batch->updated_by = $user->id;
    $batch->updated_time = now();
    $batch->save();

    return redirect()
        ->route('action.batches.detail', ['id' => $batch->id])
        ->with('success', 'Record created successfully.');
}

public function show($id)
    {
        $batch = ActionBatchModel::findOrFail($id);
        return Inertia::render('action/batches/ActionBatchDetail', ['batch'=> $batch]);
    }


public function detail($id)
{
    $batch = ActionBatchModel::findOrFail($id);
    return Inertia::render('action/batches/ActionBatchDetail', ['batch' => $batch]);
}
}