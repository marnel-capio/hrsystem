<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntermediateApplicantSkill extends Model
{
    use HasFactory;

    protected $table = 'intermediate_applicants_skills';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'intermediate_applicant_id',
        'skill',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public function applicant()
    {
        return $this->belongsTo(IntermediateApplicant::class, 'intermediate_applicant_id', 'id');
    }
}
