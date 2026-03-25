<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActionApplicant extends Model
{
    use HasFactory;

    protected $table = 'action_applicants';
    protected $primaryKey = 'id';
    public $timestamps = false; // we use created_time / updated_time

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

    /**
     * Get all applicants with mapped human-readable fields
     */
    public static function getAllActionApplicants()
    {
        $sourceTypes = config('constants.sourceTypes');
        $sources = config('constants.sources');
        $genders = config('constants.genders');

        return self::orderBy('created_time', 'desc')
            ->get()
            ->map(function ($a) use ($sourceTypes, $sources, $genders) {
                return [
                    'id' => $a->id,
                    'source_type' => $sourceTypes[$a->source_type] ?? '',
                    'source' => $sources[$a->source] ?? '',
                    'other_source' => $a->other_source,
                    'last_name' => $a->last_name,
                    'first_name' => $a->first_name,
                    'middle_name' => $a->middle_name,
                    'email_address' => $a->email_address,
                    'gender' => $genders[$a->gender] ?? '',
                    'age' => $a->age,
                    'school' => $a->school,
                    'degree' => $a->degree,
                    'others_degree' => $a->others_degree,
                    'expected_graduation' => $a->expected_graduation,
                    'awards_recognition' => $a->awards_recognition,
                    'other_examination_certificate' => $a->other_examination_certificate,
                    'thesis_project' => $a->thesis_project,
                    'extra_curricular' => $a->extra_curricular,
                    'remarks' => $a->remarks,
                ];
            });
    }

    /**
     * Create a new applicant
     */
    public static function createApplicant(array $data)
    {
        $data['created_time'] = now();
        $data['updated_time'] = now();
        $data['created_by'] = Auth::id();  
        $data['updated_by'] = Auth::id();

        return self::create($data);
    }

    /**
     * Update an existing applicant
     */
    public function updateApplicant(array $data)
    {
        $data['updated_time'] = now();
        $data['updated_by'] = Auth::id();
        $this->update($data);
        return $this;
    }

    /**
     * Upsert an applicant by email
     * - If email exists, update
     * - If email does not exist, create
     */
    public static function upsertByEmail(array $data)
    {
        $email = $data['email_address'] ?? null;
        if (!$email) {
            throw new \Exception('Email address is required for upsert.');
        }

        $applicant = self::where('email_address', $email)->first();

        if ($applicant) {
            return $applicant->updateApplicant($data);
        }

        return self::createApplicant($data);
    }
}