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

public function list()
{
    return response()->json([
        'projects' => IntermediateProjectModel::orderBy('id', 'desc')->get()
    ]);
}
public function store(IntermediateRequest $request)
{
    try {
        DB::beginTransaction();

        $project = $this->intermediateService->create($request->validated(), $request);

        DB::commit();

        return redirect()->route('intermediate.requisitions.register')->with([
            'newProject' => $project, // Send new project data
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->withErrors([
            'error' => config('errors.transaction_failed.errorMessage')
        ]);
    }
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
                ->route('intermediate.projects.show', $project->id)
                ->with('success', config('errors.record_updated_successfully.errorMessage'));
    
        } catch (\Exception $e) {
    
            DB::rollBack();

            return back()->withErrors([
                'error' => config('errors.record_updated_failed.errorMessage')
            ]);
        }
    }
}