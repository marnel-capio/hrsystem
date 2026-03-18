<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActionApplicant extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'action_applicants';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'source_type',
        'source',
        'other_source',
        'last_name',
        'first_name',
        'middle_name',
        'email_address',
        'gender',
        'age',
        'school',
        'degree',
        'others_degree',
        'expected_graduation',
        'awards_recognition',
        'other_examination_certificate',
        'thesis_project',
        'extra_curricular',
        'remarks',
        'created_by',
        'updated_by',
        'created_time',
        'updated_time',
    ];

    // Disable default timestamps because we have custom column names
    public $timestamps = false;

    // Optional: cast age as integer, expected_graduation as date string
    protected $casts = [
        'age' => 'integer',
        // expected_graduation is varchar in DB, so no Carbon casting
        //'expected_graduation' => 'date',
    ];
}