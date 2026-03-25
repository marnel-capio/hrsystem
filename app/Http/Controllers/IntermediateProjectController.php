<?php

namespace App\Http\Controllers;

use App\Models\IntermediateProjectModel;
use App\Http\Requests\IntermediateRequest;
use Inertia\Inertia;
use App\Services\IntermediateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class IntermediateProjectController extends Controller
{
    protected $intermediateService;
 
    public function __construct(IntermediateService $intermediateService)
    {
        $this->intermediateService = $intermediateService;
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

    public function create()
    {
        return Inertia::render('intermediate/projects/ProjectRegister');
    }
  
    public function store(IntermediateRequest $request)
    {
        try {
    
            DB::beginTransaction();
    
            // SIMULATE ERROR
            //throw new \Exception("Test error");
    
            $project = $this->intermediateService->create($request->validated(), $request);
    
            DB::commit();
    
            return redirect()
                ->route('intermediate.projects.show', ['id' => $project->id])
                ->with('success', config('errors.record_created_successfully.errorMessage'));
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()->withErrors([
                'error' => config('errors.transaction_failed.errorMessage')
            ]);
        }
    }

    public function show($id)
    {
        $project = IntermediateProjectModel::findOrFail($id);
 
        $createdByUser = \App\Models\User::find($project->created_by);
        $updatedByUser = \App\Models\User::find($project->updated_by);
 
        $project->created_by_name = $createdByUser ? $createdByUser->first_name . ' ' . $createdByUser->last_name : 'Unknown';
        $project->updated_by_name = $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : 'Unknown';
        return Inertia::render('intermediate/projects/ProjectDetail', [
            'project' => $project,
            'user_permissions' => auth()->user()->permissions,
        ]);
    }



    public function edit($id)
    {
        $project = IntermediateProjectModel::findOrFail($id);
    
        return Inertia::render('intermediate/projects/ProjectEdit', [
            'project' => $project,
            'user_permissions' => auth()->user()->permissions,
        ]);
    }

    public function update(IntermediateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
    
            // TEST ERROR
            //throw new \Exception("Test error");
    
            $data = $request->validated();
            $data['id'] = $id;
    
            $project = $this->intermediateService->update($data, $request);
    
            DB::commit();
    
            return redirect()
                ->route('intermediate.project.show', $project->id)
                ->with('success', config('errors.record_updated_successfully.errorMessage'));
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()->withErrors([
                'error' => config('errors.record_updated_failed.errorMessage')
            ]);
        }
    }
}