<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class IntermediateRequisitionModel extends Model
{
    protected $table = 'resource_requisitions';
 
    public $timestamps = true;
 
    protected $fillable = [
        'engagement_type',
        'sourcing_type',
        'request_type',
        'replacement_due_to',
        'person_to_replace',
        'location_assignment',
        'project_id',
        'project_description',
        'business_unit',
        'resource',
        'practice',
        'no_resources_needed',
        'start_date',
        'duration_project_engagement',
        'required_skills',
        'preferred_skilss',
        'role',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];
 
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('project', function ($q2) use ($search) {
                    $q2->where('project_name', 'like', "%{$search}%");
                })
                ->orWhere('location_assignment', 'like', "%{$search}%")
                ->orWhere('start_date', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getLocationAssignmentLabelAttribute()
    {
        return match ((int) $this->location_assignment) {
            1 => 'Alabang',
            2 => 'Makati',
            3 => 'Cebu',
            4 => 'Japan',
            5 => 'China',
            6 => 'Other',
            default => 'Unknown',
        };
    }
 
    public static function getPaginated($search = null, $perPage = 20)
    {
        return self::query()
            ->with('project') 
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function project()
    {
        return $this->belongsTo(IntermediateProjectModel::class, 'project_id');
    }

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';
}