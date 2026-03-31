<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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

    // Cast dates properly
    protected $casts = [
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
        'expected_graduation' => 'string',
        'age' => 'integer',
    ];

    // Mutators to clean data
    protected $attributes = [
        'source_type' => '',
        'source' => '',
    ];

    public static function updateOrCreateFromRow(array $row, $gender, $source_type, $source, $other_source, $createdTime, $updatedTime)
    {
        $nameParts = explode(',', $row['Full Name (Last Name, First Name, Middle Initial)'] ?? '');
        $last = trim($nameParts[0] ?? '');
        $first = isset($nameParts[1]) ? trim(explode(' ', trim($nameParts[1]))[0]) : '';
        $middle = isset($nameParts[1]) ? trim(explode(' ', trim($nameParts[1]))[1] ?? '') : '';

        $email = trim($row['Email Address'] ?? '');

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
                'extra_curricular' => substr(trim($row['Extra-curricular Activities'] ?? ''), 0, 255),
                'created_by' => Auth::id(),
                'created_time' => now(),
                'updated_by' => Auth::id(),
                'updated_time' => now(),
            ]
        );
    }

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

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function applications()
    {
        return $this->hasMany(ActionApplication::class, 'action_applicant_id');
    }

    public function programmingLanguages()
    {
        return $this->hasMany(ActionApplicantProgrammingLanguage::class, 'action_applicant_id', 'id');
    }

    public function updateWithRequest(array $data, ?int $userId = null)
    {
        // Handle source / other_source logic first
        $sourceType = (int) ($data['source_type'] ?? $this->source_type);

        if ($sourceType === 3) {
            $this->source = $data['source'] ?? null;
            $this->other_source = $data['other_source'] ?? null; // keep if provided
        } elseif (in_array($sourceType, [1, 2, 4, 5])) {
            $this->other_source = $data['other_source'] ?? null;
            $this->source = null;
        } else {
            $this->source = $data['source'] ?? null;
            $this->other_source = $data['other_source'] ?? null;
        }

        // Mass assignment for fillable fields (except created_by / created_time)
        $fieldsToUpdate = [
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
        ];

        foreach ($fieldsToUpdate as $field) {
            if (isset($data[$field])) {
                $this->$field = $data[$field];
            }
        }

        // Update audit fields
        $this->updated_by = $userId;
        $this->updated_time = now();

        return $this->save();
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
