<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        // 'expected_graduation' => 'date',
    ];

    public static function createApplicant($request)
    {
        $now = now();

        return self::create([
            'source_type' => $request->source_type,
            'source' => $request->source,
            'other_source' => $request->other_source,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'email_address' => $request->email_address,
            'gender' => $request->gender,
            'age' => $request->age,
            'school' => $request->school,
            'degree' => $request->degree,
            'others_degree' => $request->others_degree,
            'expected_graduation' => $request->expected_graduation,
            'awards_recognition' => $request->awards_recognition,
            'other_examination_certificate' => $request->other_examination_certificate,
            'thesis_project' => $request->thesis_project,
            'extra_curricular' => $request->extra_curricular,
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_time' => $now,
            'updated_time' => $now,
        ]);
    }
}
