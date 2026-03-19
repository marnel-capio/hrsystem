<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionApplicant extends Model
{
    protected $table = 'action_applicants';

    public $timestamps = false; // since you use created_time / updated_time

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
        'created_time',
        'updated_by',
        'updated_time',
    ];
}