<?php

namespace App\Http\Controllers;

use App\Models\IntermediateProjectModel;
use Inertia\Inertia;
use App\Services\IntermediateProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntermediateProjectController extends Controller
{
    protected $intermediateProjectService;
 
    public function __construct(IntermediateProjectService $intermediateProjectService)
    {
        $this->intermediateProjectService = $intermediateProjectService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->input('search');

        $projects = IntermediateProjectModel::getPaginated($search, perPage: 20);
        $projectsTotal = IntermediateProjectModel::count();

        return Inertia::render('intermediate/projects/ProjectList', [
            'projects' => $projects,
            'filters' => [
                'search' => $search,
            ],
            'projects_total' => $projectsTotal,
            'user_permissions' => $user->permissions,
        ]);
    }
 





    // public function create()
    // {
    //     return Inertia::render('action/batches/ActionBatchRegister');
    // }
  
    // public function store(ActionBatchRequest $request)
    // {
    //     try {
    
    //         DB::beginTransaction();
    
    //         // SIMULATE ERROR
    //         //throw new \Exception("Test error");
    
    //         $batch = $this->actionBatchService->create($request->validated(), $request);
    
    //         DB::commit();
    
    //         return redirect()
    //             ->route('action.batches.show', ['id' => $batch->id])
    //             ->with('success', config('errors.action_batch_create_success.message'));
    
    //     } catch (\Exception $e) {
    
    //         DB::rollBack();
    
    //         return back()->withErrors([
    //             'error' => config('errors.action_batch_create_error.errorMessage')
    //         ]);
    //     }
    // }
 
 
 
    // public function show($id)
    // {
    //     $batch = IntermediateProjectModel::findOrFail($id);
 
    //     $createdByUser = \App\Models\User::find($batch->created_by);
    //     $updatedByUser = \App\Models\User::find($batch->updated_by);
 
    //     $batch->created_by_name = $createdByUser ? $createdByUser->first_name . ' ' . $createdByUser->last_name : 'Unknown';
    //     $batch->updated_by_name = $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : 'Unknown';
 
    //     return Inertia::render('action/batches/ActionBatchDetail', [
    //         'batch' => $batch
    //     ]);
    // }
}