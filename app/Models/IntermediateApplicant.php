<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
        'age' => 'integer',
        'children' => 'integer',
    ];

    public function skills()
    {
        return $this->hasMany(IntermediateApplicantSkill::class, 'intermediate_applicant_id', 'id');
    }

    public static function getAllIntermediateApplicants()
    {
        $genders = config('constants.genders');
        $sourceTypes = config('constants.intermediateSourceTypes', []);
        $sources = config('constants.intermediateSources', []);

        return self::with('skills')
            ->orderBy('created_time', 'desc')
            ->get()
            ->map(function ($a) use ($genders, $sourceTypes, $sources) {
                return [
                    'id' => $a->id,
                    'registered_date' => $a->registered_date,
                    'registered_by' => $a->registered_by,
                    'source_type' => $sourceTypes[$a->source_type] ?? $a->source_type,
                    'source' => $sources[$a->source] ?? $a->source,
                    'other_source' => $a->other_source,
                    'last_name' => $a->last_name,
                    'first_name' => $a->first_name,
                    'middle_name' => $a->middle_name,
                    'gender' => $genders[$a->gender] ?? '',
                    'age' => $a->age,
                    'address' => $a->address,
                    'birthdate' => $a->birthdate,
                    'email_address' => $a->email_address,
                    'contact_no' => $a->contact_no,
                    'school_graduated_from' => $a->school_graduated_from,
                    'course' => $a->course,
                    'year_attended' => $a->year_attended,
                    'others' => $a->others,
                    'spouse_details' => $a->spouse_details,
                    'children' => $a->children,
                    'father_details' => $a->father_details,
                    'mother_details' => $a->mother_details,
                    'sibling_details' => $a->sibling_details,
                    'emergency_contact_name' => $a->emergency_contact_name,
                    'emergency_contact_number' => $a->emergency_contact_number,
                    'emergency_contact_address' => $a->emergency_contact_address,
                    'remarks' => $a->remarks,
                    'skills' => $a->skills,
                ];
            });
    }

    public static function createApplicant(array $data)
    {
        $data['registered_date'] = now();
        $data['registered_by'] = Auth::id();
        $data['created_by'] = Auth::id();
        $data['created_time'] = now();
        $data['updated_by'] = Auth::id();
        $data['updated_time'] = now();

        return self::create($data);
    }

    public function updateApplicant(array $data)
    {
        $data['updated_by'] = Auth::id();
        $data['updated_time'] = now();

        $this->update($data);

        return $this;
    }

    public static function upsertByEmail(array $data)
    {
        $email = $data['email_address'] ?? null;

        if (! $email) {
            throw new \Exception('Email address is required for upsert.');
        }

        $applicant = self::where('email_address', $email)->first();

        if ($applicant) {
            return $applicant->updateApplicant($data);
        }

        return self::createApplicant($data);
    }
}
