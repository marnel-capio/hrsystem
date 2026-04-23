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
        'business_unit',
        'resource',
        'practice',
        'no_resources_needed',
        'start_date',
        'duration_project_engagement',
        'required_skills',
        'preferred_skilLs',
        'role',
        'custom?_location',
        'expected_salary_range',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {

            $locationMap = [
                'alabang' => 1,
                'makati' => 2,
                'cebu' => 3,
                'japan' => 4,
                'china' => 5,
                'other' => 6,
            ];

            $query->where(function ($q) use ($search, $locationMap) {

                $q->whereHas('project', function ($q2) use ($search) {
                    $q2->where('project_name', 'like', "%{$search}%");
                });

                $searchLower = strtolower($search);
                $months = [
                    'january' => 1,
                    'february' => 2,
                    'march' => 3,
                    'april' => 4,
                    'may' => 5,
                    'june' => 6,
                    'july' => 7,
                    'august' => 8,
                    'september' => 9,
                    'october' => 10,
                    'november' => 11,
                    'december' => 12,
                ];

                if (array_key_exists($searchLower, $months)) {
                    $month = $months[$searchLower];
                    $q->orWhereMonth('start_date', '=', $month);
                } else {
                    $q->orWhere('start_date', 'like', "%{$search}%");
                }

                $q->orWhereHas('requestedBy', function ($q3) use ($search) {
                    $q3->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });

                foreach ($locationMap as $key => $value) {
                    if (stripos($key, $searchLower) !== false) {
                        $q->orWhere('location_assignment', $value);
                    }
                }

                $q->orWhere('resource', 'like', "%{$search}%");
            });
        }
    }

    public function project()
    {
        return $this->belongsTo(IntermediateProjectModel::class, 'project_id');
    }

    public function getProjectDescriptionAttribute()
    {
        return $this->project ? $this->project->project_description : null;
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')
            ->select('id', 'first_name', 'last_name');
    }

    public function getCustomLocationNameAttribute()
    {
        if ($this->location_assignment == 6) {
            return $this->custom_location;
        }

        $locationMap = [
            1 => 'Alabang',
            2 => 'Makati',
            3 => 'Cebu',
            4 => 'Japan',
            5 => 'China',
            6 => 'Other',
        ];

        return $locationMap[$this->location_assignment] ?? 'Unknown';
    }

    public static function getPaginated($search = null, $perPage = 20)
    {
        return self::query()
            ->select([
                'id',
                'project_id',
                'resource',
                'location_assignment',
                'start_date',
                'created_by',
                'created_time',
            ])
            ->with([
                'project:id,project_name,project_description',
                'requestedBy:id,first_name,last_name',
            ])
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getLocationAssignmentLabelAttribute()
    {
        if ($this->location_assignment == 6) {
            return $this->custom_location;
        }

        $locationMap = [
            1 => 'Alabang',
            2 => 'Makati',
            3 => 'Cebu',
            4 => 'Japan',
            5 => 'China',
            6 => 'Other',
        ];

        return $locationMap[$this->location_assignment] ?? 'Not Assigned';
    }

    const CREATED_AT = 'created_time';

    const UPDATED_AT = 'updated_time';
}
