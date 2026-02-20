<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ResourceSchedule extends Model
{
    /**
     * Disable default timestamps — we use custom fields
     */
    public $timestamps = false;

    /**
     * Mass assignable fields (not used ywt, but kept safe)
     */
    protected $fillable = [
        'batch_name',
        'target_location',
        'target_trainees',
        'deployment_date',
        'wbs',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'wbs'          => 'array',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    /* ============================================================
     * QUERY HELPERS
     * ============================================================ */

    /**
     * Scope: Order by created_time
     */
    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('created_time', 'desc');
    }

    /**
     * Get list page data
     */
    public static function listPageData()
    {
        return static::ordered()->get();
    }
}