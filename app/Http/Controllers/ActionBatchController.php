<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
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

    $validated = $request->validate([
        'action_batch' => 'required|string|max:20|unique:action_batches,action_batch',
        'target_trainees' => 'required|integer|max:99',
        'target_date' => 'required|string|max:10',
        'remarks' => 'nullable|string|max:1024',
    ], [
        'action_batch.unique' => 'Action Batch already exist.'
    ]);

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

    DB::table('logs')->insert([
        'module' => 'Action',
        'activity' => 'Created a new action batch ' . $batch->action_batch,
        'ip_address' => $request->ip(),
        'created_by' => $user->id,
        'updated_by' => $user->id,
        'create_time' => now(),
        'update_time' => now(),
    ]);

    return redirect()
        ->route('action.batches.detail', ['id' => $batch->id])
        ->with('success', 'Record created successfully.');
}


//Link from List to Detail
public function show($id)
{
    $user = Auth::user();

    if (!in_array($user->permissions, [1, 2, 3])) {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Access denied: You are not authorized to view this page.');
    }
    
    $batch = ActionBatchModel::findOrFail($id);
    $createdByUser = \App\Models\User::find($batch->created_by);
    $updatedByUser = \App\Models\User::find($batch->updated_by);
    $batch->created_by_name = $createdByUser ? $createdByUser->first_name . ' ' . $createdByUser->last_name : 'Unknown';
    $batch->updated_by_name = $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : 'Unknown';
    
    return Inertia::render('action/batches/ActionBatchDetail', [
        'batch' => $batch,
        'user_permissions' => $user->permissions, 
    ]);
}

   
//Link from Register to Detail
public function detail($id)
{
    $user = Auth::user();

    if (!in_array($user->permissions, [1, 2, 3])) {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Access denied: You are not authorized to view this page.');
    }
    $batch = ActionBatchModel::findOrFail($id);
    $createdByUser = \App\Models\User::find($batch->created_by);
    $updatedByUser = \App\Models\User::find($batch->updated_by);
    $batch->created_by_name = $createdByUser ? $createdByUser->first_name . ' ' . $createdByUser->last_name : 'Unknown';
    $batch->updated_by_name = $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : 'Unknown';

    return Inertia::render('action/batches/ActionBatchDetail', ['batch' => $batch, 'user_permissions' => $user->permissions]);
}
}