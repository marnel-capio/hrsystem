<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActionBatchModel extends Model
{
    protected $table = 'action_batches';

    public $timestamps = true;

    protected $fillable = [
        'action_batch',
        'target_trainees',
        'target_date',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('action_batch', 'like', "%{$search}%");
        }

        return $query;
    }

    public static function getPaginated($search = null, $perPage = 20)
    {
        return self::query()
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }



    //FUNCTIONS TO USE IN RESOURCE SCHEDULE
    public static function getActionBatches($excludeScheduled = true)
    {
        $scheduledBatchIds = ResourceSchedule::pluck('action_batch_id')->toArray();

        $query = DB::table('action_batches')->select('id', 'action_batch', 'target_trainees', 'target_date');

        if ($excludeScheduled) {
            $query->whereNotIn('id', $scheduledBatchIds);
        } else {
            $query->whereIn('id', $scheduledBatchIds);
        }

        return $query->get();
    }

    public function getRecruitmentProjection($batchId = null)
    {
        $batchId = $batchId ?? $this->action_batch_id;

        return DB::table('action_applicant_applications')
            ->where('action_batch_id', $batchId)
            ->get();
    }

        // Get previous batch name
    public static function prevBatchName($id): ?string
    {
        return DB::table('action_batches')
            ->where('id', $id)
            ->value('action_batch');
    }


    //END OF FUNCTIONS TO USE FOR RS


    //FUNCTIONS TO USE FOR ACTION APPLICATIONS
    public static function getWithTargetLocationAndApplications()
    {
    return self::with(['applications.applicant', 'resourceSchedule'])
        ->orderBy('id', 'desc')
        ->get();
    }
    public function resourceSchedule()
    {
        return $this->hasOne(ResourceSchedule::class, 'action_batch_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(ActionApplication::class, 'action_batch_id', 'id');
    }

    //END OF FUNCTIONS TO USE FOR ACTION APPLICATIONS


}
