<?php
 
namespace App\Services;
 
use App\Models\ActionBatchModel;
use Illuminate\Support\Facades\DB;
 
class ActionBatchService
{
    public function create($data, $request)
    {
        $batch = new ActionBatchModel();
 
        $batch->action_batch = strtoupper($data['action_batch']);
        $batch->target_trainees = $data['target_trainees'];
        $batch->target_date = $data['target_date'];
        $batch->remarks = $data['remarks'] ?? null;
 
        $batch->created_by = auth()->user()->id;
        $batch->created_time = now();
        $batch->updated_by = auth()->user()->id;
        $batch->updated_time = now();
 
        $batch->save();
 
        DB::table('logs')->insert([
            'module' => 'Action',
            'activity' => 'Created a new action batch ' . $batch->action_batch,
            'ip_address' => $request->ip(),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'create_time' => now(),
            'update_time' => now(),
        ]);
 
        return $batch;
    }

    public function update($data, $request)
    {
        $batch = ActionBatchModel::findOrFail($data['id']);

        $batch->action_batch = strtoupper($data['action_batch']);
        $batch->target_trainees = $data['target_trainees'];
        $batch->target_date = $data['target_date'];
        $batch->remarks = $data['remarks'] ?? null;

        $batch->updated_by = auth()->user()->id;
        $batch->updated_time = now();

        $batch->save();

        DB::table('logs')->insert([
            'module' => 'Action',
            'activity' => 'Updated ACTION batch ' . $batch->action_batch,
            'ip_address' => $request->ip(),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'create_time' => now(),
            'update_time' => now(),
        ]);

        return $batch;
    }
 
}
 