<?php

namespace App\Http\Controllers;

use App\Models\ActionBatchModel;
use App\Http\Requests\ActionBatchRequest;
use Inertia\Inertia;
use App\Services\ActionBatchService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionBatchController extends Controller
{
    protected $actionBatchService;
 
    public function __construct(ActionBatchService $actionBatchService)
    {
        $this->actionBatchService = $actionBatchService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Remove the permission check from here

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
        return Inertia::render('action/batches/ActionBatchRegister');
    }
  
    public function store(ActionBatchRequest $request)
    {
        try {
    
            DB::beginTransaction();
    
            // SIMULATE ERROR
            //throw new \Exception("Test error");
    
            $batch = $this->actionBatchService->create($request->validated(), $request);
    
            DB::commit();
    
            return redirect()
                ->route('action.batches.show', ['id' => $batch->id])
                ->with('success', config('errors.action_batch_create_success.message'));
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()->withErrors([
                'error' => config('errors.action_batch_create_error.errorMessage')
            ]);
        }
    }
 
 
 
    public function show($id)
    {
        $batch = ActionBatchModel::findOrFail($id);
 
        $createdByUser = \App\Models\User::find($batch->created_by);
        $updatedByUser = \App\Models\User::find($batch->updated_by);
 
        $batch->created_by_name = $createdByUser ? $createdByUser->first_name . ' ' . $createdByUser->last_name : 'Unknown';
        $batch->updated_by_name = $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : 'Unknown';
 
        return Inertia::render('action/batches/ActionBatchDetail', [
            'batch' => $batch
        ]);
    }
}

// public function edit($id)
// {
//     $user = Auth::user();

//     if (!in_array($user->permissions, [1, 2])) {
//         return redirect()
//             ->route('dashboard')
//             ->with('error', 'Access denied: You are not authorized to view this page.');
//     }

//     $batch = ActionBatchModel::findOrFail($id);
//     return Inertia::render('action/batches/ActionBatchEdit', [
//         'batch' => $batch,
//         'user_permissions' => $user->permissions,
//     ]);
// }

// public function update(Request $request, $id)
// {
//     $user = Auth::user();

//     if (!in_array($user->permissions, [1, 2])) {
//         return redirect()
//             ->route('dashboard')
//             ->with('error', 'Access denied: You are not authorized to edit this batch.');
//     }

//     $validated = $request->validate([
//         'action_batch' => 'required|string|max:20',
//         'target_trainees' => 'required|integer|max:99',
//         'target_date' => 'required|date',
//         'remarks' => 'nullable|string|max:1024',
//     ]);

//     $batch = ActionBatchModel::findOrFail($id);
//     $batch->action_batch = strtoupper($validated['action_batch']);
//     $batch->target_trainees = $validated['target_trainees'];
//     $batch->target_date = $validated['target_date'];
//     $batch->remarks = $validated['remarks'];
//     $batch->updated_by = $user->id;
//     $batch->updated_time = now();
//     $batch->save();

//     DB::table('logs')->insert([
//         'module' => 'Action',
//         'activity' => 'Updated Action Batch ' . $batch->action_batch,
//         'ip_address' => $request->ip(),
//         'created_by' => $user->id,
//         'updated_by' => $user->id,
//         'create_time' => now(),
//         'update_time' => now(),
//     ]);

//     return redirect()->route('action.batches.show', ['id' => $batch->id])
//         ->with('success', 'Action batch updated successfully.');
// }