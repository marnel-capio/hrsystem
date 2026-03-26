<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionApplicantProgrammingLanguage extends Model
{
    // Table name (optional, for clarity)
    protected $table = 'action_applicants_programming_languages';

    // Mass assignable fields
    protected $fillable = [
        'action_applicant_id',
        'program_language',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    // Disable default Laravel timestamps
    public $timestamps = false;

    // Relationship to applicant (optional)
    public function applicant()
    {
        return $this->belongsTo(ActionApplicant::class, 'action_applicant_id');
    }
    
}