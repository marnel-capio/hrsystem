<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntermediateApplicationWorkExperience extends Model
{
    // Table name (optional if Laravel can infer)
    protected $table = 'intermediate_applicants_work_experiences';

    // Primary key (optional if Laravel uses 'id')
    protected $primaryKey = 'id';

    // Disable default timestamps because you have custom column names
    public $timestamps = false;

    // Mass assignable fields
    protected $fillable = [
        'intermediate_applicant_id',
        'employer',
        'company_address',
        'job_title',
        'date_employed',
        'work_description',
        'salary',
        'reason_for_leaving',
        'name_supervisor',
        'remarks',
        'is_deleted',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    // Casts
    protected $casts = [
        'is_deleted' => 'boolean',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    /**
     * Scope for non-deleted records
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0);
    }

    /**
     * Relation to IntermediateApplicant
     */
    public function applicant()
    {
        return $this->belongsTo(IntermediateApplicant::class, 'intermediate_applicant_id');
    }
}