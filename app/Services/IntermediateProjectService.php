<?php
 
namespace App\Services;
 
use App\Models\IntermediateProjectModel;
use Illuminate\Support\Facades\DB;
 
class IntermediateProjectService
{
    public function create($data, $request)
    {
        $project = new IntermediateProjectModel();
 
        $project->project_name = strtoupper($data['project_name']);
        $project->remarks = $data['remarks'] ?? null;
 
        $project->created_by = auth()->user()->id;
        $project->created_time = now();
        $project->updated_by = auth()->user()->id;
        $project->updated_time = now();
 
        $project->save();
 
        DB::table('logs')->insert([
            'module' => 'Intermediate',
            'activity' => 'Created a new project ' . $project->project_name,
            'ip_address' => $request->ip(),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'create_time' => now(),
            'update_time' => now(),
        ]);
 
        return $project;
    }
}
 