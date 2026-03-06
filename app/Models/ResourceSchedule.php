<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResourceSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action_batch_id',
        'prev_batch_id',
        'target_location',
        'target_trainees',
        'deployment_date',
        'contact_schools_startdate',
        'contact_schools_enddate',
        'source_testing_startdate',
        'source_testing_enddate',
        'initial_interviews_startdate',
        'initial_interviews_enddate',
        'final_interviews_startdate',
        'final_interviews_enddate',
        'contract_offers_startdate',
        'contract_offers_enddate',
        'requirements_startdate',
        'requirements_enddate',
        'training_startdate',
        'training_enddate',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    /**
     * Relationships
     */
    public function actionBatch()
    {
        return $this->belongsTo(ActionBatchModel::class, 'action_batch_id');
    }

    /**
     * Helper methods
     */
    public static function listPageData(?string $search = null)
    {
        return static::query()
            ->select('resource_schedules.*', 'action_batches.action_batch', 'action_batches.target_trainees')
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('action_batches.action_batch', 'like', "%{$search}%")
                      ->orWhere('resource_schedules.target_location', 'like', "%{$search}%")
                      ->orWhereRaw("DATE_FORMAT(resource_schedules.deployment_date, '%M %Y') LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('resource_schedules.created_time', 'desc')
            ->get();
    }

    public static function getActionBatches($excludeScheduled = true)
    {
        $scheduledBatchIds = self::pluck('action_batch_id')->toArray();

        $query = DB::table('action_batches')->select('id', 'action_batch', 'target_trainees', 'target_date');

        if ($excludeScheduled) {
            $query->whereNotIn('id', $scheduledBatchIds);
        } else {
            $query->whereIn('id', $scheduledBatchIds);
        }

        return $query->get();
    }

    public static function getWithActionBatch($id)
    {
        return self::select('resource_schedules.*', 'action_batches.action_batch')
            ->join('action_batches', 'resource_schedules.action_batch_id', '=', 'action_batches.id')
            ->where('resource_schedules.id', $id)
            ->firstOrFail();
    }
}