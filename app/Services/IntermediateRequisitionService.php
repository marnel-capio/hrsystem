<?php
 
namespace App\Services;
 
use App\Models\IntermediateRequisitionModel;
use App\Models\IntermediateProjectModel;

use Illuminate\Support\Facades\DB;
 
class IntermediateRequisitionService
{
    public function create($data, $request)
{
    $requisition = new IntermediateRequisitionModel();

    $project = IntermediateProjectModel::find($data['project_id']);

    $requisition->engagement_type = $data['engagement_type'];
    $requisition->sourcing_type = $data['sourcing_type'];
    $requisition->request_type = $data['request_type'];
    $requisition->replacement_due_to = $data['replacement_due_to'] ?? null;
    $requisition->person_to_replace = $data['person_to_replace'] ?? null;

    $requisition->location_assignment = $data['location_assignment'];
    $requisition->project_id = $data['project_id'];
    $requisition->business_unit = $data['business_unit'];
    $requisition->resource = $data['resource']?? null;
    $requisition->practice = $data['practice']?? null;
    $requisition->no_resources_needed = $data['no_resources_needed']?? null;
    $requisition->start_date = $data['start_date']?? null;
    $requisition->duration_project_engagement = $data['duration_project_engagement']?? null;
    $requisition->required_skills = $data['required_skills']?? null;
    $requisition->preferred_skills = $data['preferred_skills'] ?? null;
    $requisition->role = $data['role']?? null;
    $requisition->custom_location = $data['custom_location'];
    $requisition->expected_salary_range = $data['expected_salary_range']?? null;
    $requisition->remarks = $data['remarks'] ?? null;


    // Ensure created and updated timestamps are set manually
    $requisition->created_by = auth()->user()->id;
    $requisition->created_time = now();
    $requisition->updated_by = auth()->user()->id;
    $requisition->updated_time = now();

    // Save the requisition
    $requisition->save();

    // Log the activity
    DB::table('logs')->insert([
        'module' => 'Intermediate',
        'activity' => 'Created a new Resource Requisition for ' . ($project->project_name ?? 'Unknown Project'),
        'ip_address' => $request->ip(),
        'created_by' => auth()->user()->id,
        'updated_by' => auth()->user()->id,
        'create_time' => now(),
        'update_time' => now(),
    ]);

    // Return the created requisition
    return $requisition;
}

}
 