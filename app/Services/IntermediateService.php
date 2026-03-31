<?php
 
namespace App\Services;
 
use App\Models\IntermediateProjectModel;
use Illuminate\Support\Facades\DB;
 
class IntermediateService
{
    public function create($data, $request)
    {
        $project = new IntermediateProjectModel();
 
        $project->project_name = $data['project_name'];
        $project->remarks = $data['remarks'] ?? null;
 
        $project->created_by = auth()->user()->id;
        $project->created_time = now();
        $project->updated_by = auth()->user()->id;
        $project->updated_time = now();
 
        $project->save();
 
        DB::table('logs')->insert([
            'module' => 'Intermediate',
            'activity' => 'Created a new Intermediate Project ' . $project->project_name,
            'ip_address' => $request->ip(),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'create_time' => now(),
            'update_time' => now(),
        ]);
 
        return $project;
    }


    public function update($data, $request)
    {
        $project = IntermediateProjectModel::findOrFail($data['id']);

        $oldData = [
            'project_name' => $project->project_name,
            'remarks' => $project->remarks,
        ];

        $project->project_name = $data['project_name'];
        $project->remarks = $data['remarks'] ?? null;
        $project->updated_by = auth()->user()->id;
        $project->updated_time = now();

        $project->save();

        $activityLines = [];
        $activityLines[] = "Updated remarks for project: {$project->project_name}.";
        $activityLines[] = "Details:";

        $fields = ['project_name', 'remarks'];

        foreach ($fields as $field) {
            $oldValue = $oldData[$field] ?? '[empty]';
            $newValue = $project->$field ?? '[empty]';

            $oldValueStr = $oldValue === '' ? '[empty]' : $oldValue;
            $newValueStr = $newValue === '' ? '[empty]' : $newValue;

            if ($oldValueStr !== $newValueStr) {
                $activityLines[] = "{$field}: {$oldValueStr} -> {$newValueStr}";
            }
        }

        $activity = implode("\n", $activityLines);

        DB::table('logs')->insert([
            'module' => 'Intermediate',
            'activity' => $activity,
            'ip_address' => $request->ip() ?? '0.0.0.0',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'create_time' => now(),
            'update_time' => now(),
        ]);

        return $project;
    }
}
 