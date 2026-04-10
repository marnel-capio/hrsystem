<?php
 
namespace App\Services;
 
use App\Models\IntermediateRequisitionModel;
use Illuminate\Support\Facades\DB;
 
class IntermediateRequisitionService
{
    public function create($data, $request)
    {
        $requisition = new IntermediateRequisitionModel();
 
        $requisition->project_name = $data['project_name'];
        $requisition->remarks = $data['remarks'] ?? null;
 
        $requisition->created_by = auth()->user()->id;
        $requisition->created_time = now();
        $requisition->updated_by = auth()->user()->id;
        $requisition->updated_time = now();
 
        $requisition->save();
 
        DB::table('logs')->insert([
            'module' => 'Intermediate',
            'activity' => 'Created a new Resource Requisition for' . $requisition->project_name,
            'ip_address' => $request->ip(),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'create_time' => now(),
            'update_time' => now(),
        ]);
 
        return $requisition;
    }


    public function update($data, $request)
    {
        $project = IntermediateRequisitionModel::findOrFail($data['id']);

        $oldData = [
            'engagement_type' => $project->engagement_type,
            'sourcing_type' => $project->sourcing_type,
        ];

        $project->project_name = $data['project_name'];
        $project->remarks = $data['remarks'] ?? null;
        $project->updated_by = auth()->user()->id;
        $project->updated_time = now();

        $project->save();

        $activityLines = [];
        $activityLines[] = "Updated resource requisition: {$project->project_name}.";
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
 