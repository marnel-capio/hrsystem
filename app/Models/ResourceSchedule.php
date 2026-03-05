<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ResourceSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action_batch_id',
        'target_location',
        'target_trainees',
        'deployment_date',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'created_time'    => 'datetime',
        'updated_time'    => 'datetime',
        'deployment_date' => 'date',
    ];

    /**
     * Relationship to action_batches
     */
    public function actionBatch()
    {
        return $this->belongsTo(ActionBatch::class, 'action_batch_id', 'id');
    }

    /**
     * Get list page data with search functionality
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
}