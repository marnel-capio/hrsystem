<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntermediateApplicantSkill extends Model
{
    protected $table = 'intermediate_applicants_skills';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'intermediate_applicant_id',
        'skill',
        'remarks',
        'is_deleted',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    /**
     * Indicates if the model should be timestamped.
     * (You are using custom timestamp columns)
     */
    public $timestamps = false;

    /**
     * Default attribute casting
     */
    protected $casts = [
        'is_deleted' => 'integer',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    /**
     * Scope: only active (not deleted) records
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0);
    }

    /**
     * Scope: only deleted records
     */
    public function scopeDeleted($query)
    {
        return $query->where('is_deleted', 1);
    }

    public function applicant()
    {
        return $this->belongsTo(IntermediateApplicant::class, 'intermediate_applicant_id');
    }
}   
