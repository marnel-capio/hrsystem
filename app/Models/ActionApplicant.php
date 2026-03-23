<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ActionApplicant extends Model
{
    protected $table = 'action_applicants';
    public $timestamps = false;
    
    protected $fillable = [
        'source_type', // Keep as VARCHAR/TEXT - NOT integer
        'source',      // Keep as VARCHAR/TEXT
        'last_name',
        'first_name',
        'middle_name',
        'email_address',
        'gender',
        'age',
        'school',
        'degree',
        'others_degree',
        'expected_graduation', // VARCHAR - store as string "2021"
        'awards_recognition',
        'other_examination_certificate',
        'thesis_project',
        'extra_curricular',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    // Cast dates properly
    protected $casts = [
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
        'expected_graduation' => 'string', // Force string
        'age' => 'integer',
    ];

    // Mutators to clean data
    protected $attributes = [
        'source_type' => '',
        'source' => '',
    ];

// ActionApplicant.php
public static function updateOrCreateFromRow(array $row, $gender, $source_type, $source, $other_source, $now)
{
    $nameParts = explode(',', $row['Full Name (Last Name, First Name, Middle Initial)'] ?? '');
    $last = trim($nameParts[0] ?? '');
    $first = isset($nameParts[1]) ? trim(explode(' ', trim($nameParts[1]))[0]) : '';
    $middle = isset($nameParts[1]) ? trim(explode(' ', trim($nameParts[1]))[1] ?? '') : '';

    $email = trim($row['Email Address'] ?? '');

    // updateOrCreate by email
    return self::updateOrCreate(
        ['email_address' => $email],
        [
            'source_type' => $source_type,
            'source' => $source,
            'other_source' => $other_source,
            'last_name' => $last,
            'first_name' => $first,
            'middle_name' => $middle,
            'gender' => $gender,
            'age' => (int)($row['Age'] ?? 0),
            'school' => trim($row['School '] ?? ''),
            'degree' => trim($row["Bachelor's Degree"] ?? ''),
            'others_degree' => trim($row["If others, please indicate below.\nWrite NA if not applicable (if degree is among the choices from previous question)"] ?? ''),
            'expected_graduation' => trim($row['Year of Expected Graduation'] ?? ''),
            'awards_recognition' => trim($row['Awards/ Recognition '] ?? ''),
            'other_examination_certificate' => trim($row['Other Examinations/ Certifications taken'] ?? ''),
            'thesis_project' => trim($row['Thesis Project'] ?? ''),
            'extra_curricular' => trim($row['Extra-curricular Activities'] ?? ''),
            'created_by' => Auth::id(),
            'created_time' => $now,
            'updated_by' => Auth::id(),
            'updated_time' => $now,
        ]
    );
}
}