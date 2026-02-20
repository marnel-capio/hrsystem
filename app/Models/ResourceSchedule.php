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


    /**
     * Get list page data
     */
    public static function listPageData()
    {
        return static::orderBy('created_time', 'desc')->get();
    }

    
    /**
     * Search
     */
    public function scopeSearch($query, $term)
{
    if (!$term) return $query;

    $term = strtolower($term);

    return $query->where(function ($q) use ($term) {
        $q->whereRaw("LOWER(batch_name) LIKE ?", ["%{$term}%"])
          ->orWhereRaw("LOWER(target_location) LIKE ?", ["%{$term}%"])
          ->orWhereRaw("LOWER(DATE_FORMAT(deployment_date, '%M %Y')) LIKE ?", ["%{$term}%"]);
    });
}
}