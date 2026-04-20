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

    public function getNextBatchOptions()
    {
        $lastBatch = ActionBatchModel::orderBy('id', 'desc')->first();
    
        if (!$lastBatch) {
            return ['ACTION 1', 'ACTION 1-A'];
        }
    
        $value = $lastBatch->action_batch;
    
        preg_match('/ACTION\s(\d+)(?:-([A-Z]))?/', $value, $matches);
    
        $number = (int) $matches[1];
        $suffix = $matches[2] ?? null;
    
        $options = [];
    
        if (!$suffix) {
            $options[] = "ACTION " . ($number + 1);
            $options[] = "ACTION " . ($number + 1) . "-A";
        } else {
            $nextLetter = chr(ord($suffix) + 1);
    
            if ($nextLetter <= 'Z') {
                $options[] = "ACTION {$number}-{$nextLetter}";
            }
    
            $options[] = "ACTION " . ($number + 1);
            $options[] = "ACTION " . ($number + 1) . "-A";
        }
    
        return $options;
    }
    

    //UPDATE
    public function update($data, $request)
    {
        $batch = ActionBatchModel::findOrFail($data['id']);
        
        // Store old values for comparison
        $oldData = [
            'target_trainees' => $batch->target_trainees,
            'target_date' => $batch->target_date,
            'remarks' => $batch->remarks,
        ];

        // Update fields
        $batch->target_trainees = $data['target_trainees'];
        $batch->target_date = $data['target_date'];
        $batch->remarks = $data['remarks'] ?? null;

        $batch->updated_by = auth()->user()->id;
        $batch->updated_time = now();

        $batch->save();

        // Log creation
        $activityLines = [];
        $activityLines[] = "Updated ACTION batch for {$batch->action_batch}.";
        $activityLines[] = 'Details:';

        $fields = ['action_batch', 'target_trainees', 'target_date', 'remarks'];

        foreach ($fields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $batch->$field ?? null;

            $oldValueStr = is_bool($oldValue) ? (int) $oldValue : (string) $oldValue;
            $newValueStr = is_bool($newValue) ? (int) $newValue : (string) $newValue;

            if ($oldValueStr !== $newValueStr) {
                $activityLines[] = "{$field}: {$oldValueStr} -> {$newValueStr}";
            }
        }

        $activity = implode("\n", $activityLines);

        DB::table('logs')->insert([
            'module' => 'Action',
            'activity' => $activity,
            'ip_address' => $request->ip(),
            'updated_by' => auth()->user()->id,
            'update_time' => now(),
        ]);

        return $batch;
    }
 
}
 