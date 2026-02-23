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
     * Mass assignable fields
     */
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

    /**
     * Casts
     */
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
        return $this->belongsTo(ActionBatch::class, 'action_batch_id', 'action_batch_id');
    }

    /**
     * Scope search (for index live search)
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;

        $term = strtolower($term);

        // Eager load actionBatch and filter
        return $query->with('actionBatch')->whereHas('actionBatch', function ($q) use ($term) {
            $q->whereRaw("LOWER(action_batch) LIKE ?", ["%{$term}%"]);
        })
        ->orWhereRaw("LOWER(target_location) LIKE ?", ["%{$term}%"])
        ->orWhereRaw("LOWER(DATE_FORMAT(deployment_date, '%M %Y')) LIKE ?", ["%{$term}%"]);
    }

    /**
     * Get list page data
     */
    public static function listPageData(?string $search = null)
    {
        return static::search($search)
            ->with('actionBatch')
            ->orderBy('created_time', 'desc')
            ->get();
    }
}