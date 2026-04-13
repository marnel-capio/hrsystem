<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntermediateApplicant extends Model
{
    use HasFactory;

    protected $table = 'intermediate_applicants';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'registered_date',
        'registered_by',
        'source_type',
        'source',
        'other_source',
        'last_name',
        'first_name',
        'middle_name',
        'gender',
        'age',
        'address',
        'birthdate',
        'email_address',
        'contact_no',
        'school_graduated_from',
        'course',
        'year_attended',
        'others',
        'spouse_details',
        'children',
        'father_details',
        'mother_details',
        'sibling_details',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_address',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'registered_date' => 'datetime',
        'birthdate' => 'datetime',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
        'age' => 'integer',
        'children' => 'integer',
    ];

    public function skills()
    {
        return $this->hasMany(IntermediateApplicantSkill::class, 'intermediate_applicant_id', 'id');
    }
}
