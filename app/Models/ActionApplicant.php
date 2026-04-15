<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        'contact_number',
        'address',
        'japanese_background',
        'japanese_level',
        'background_remarks',
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
        'japanese_background' => 'integer',
        'japanese_level' => 'integer',
    ];

    // Mutators to clean data
    protected $attributes = [
        'source_type' => '',
        'source' => '',
    ];

    private static function cleanUtf8($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        // Convert likely legacy encodings into UTF-8
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');

        // Drop invalid byte sequences
        $value = iconv('UTF-8', 'UTF-8//IGNORE', $value);

        // Remove ASCII control chars except tab/newline/carriage return
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);

        // Remove Unicode private-use chars (common from pasted Office bullets/icons)
        $value = preg_replace('/[\x{E000}-\x{F8FF}]/u', '', $value);

        // Normalize non-breaking space
        $value = str_replace("\xC2\xA0", ' ', $value);

        return trim($value);
    }

    public static function updateOrCreateFromRow(array $row, $gender, $source_type, $source, $other_source, $createdTime, $updatedTime)
    {
        $row = array_map(function ($value) {
            return is_string($value) ? self::cleanUtf8($value) : $value;
        }, $row);

        $fullName = self::cleanUtf8($row['Full Name (Last Name, First Name, Middle Initial)'] ?? '');
        $nameParts = explode(',', $fullName);

        $last = self::cleanUtf8($nameParts[0] ?? '');
        $firstPart = isset($nameParts[1]) ? self::cleanUtf8($nameParts[1]) : '';
        $firstSplit = preg_split('/\s+/', $firstPart);

        $first = $firstSplit[0] ?? '';
        $middle = $firstSplit[1] ?? '';

        $email = self::cleanUtf8($row['Email Address'] ?? '');

        $school = self::cleanUtf8($row['School '] ?? '');
        $degree = self::cleanUtf8($row["Bachelor's Degree"] ?? '');
        $othersDegree = self::cleanUtf8($row["If others, please indicate below.\nWrite NA if not applicable (if degree is among the choices from previous question)"] ?? '');
        $expectedGraduation = self::cleanUtf8($row['Year of Expected Graduation'] ?? '');
        $awardsRecognition = self::cleanUtf8($row['Awards/ Recognition '] ?? '');
        $otherExaminationCertificate = self::cleanUtf8($row['Other Examinations/ Certifications taken'] ?? '');
        $thesisProject = self::cleanUtf8($row['Thesis Project'] ?? '');
        $extraCurricular = mb_substr(self::cleanUtf8($row['Extra-curricular Activities'] ?? ''), 0, 255);

        $applicant = self::where('email_address', $email)->first();

        $payload = [
            'source_type' => $source_type,
            'source' => $source,
            'other_source' => mb_substr(self::cleanUtf8($other_source), 0, 80),
            'last_name' => mb_substr($last, 0, 80),
            'first_name' => mb_substr($first, 0, 80),
            'middle_name' => mb_substr($middle, 0, 80),
            'email_address' => mb_substr($email, 0, 80),
            'gender' => $gender,
            'age' => (int) ($row['Age'] ?? 0),
            'school' => mb_substr($school, 0, 80),
            'degree' => mb_substr($degree, 0, 80),
            'others_degree' => mb_substr($othersDegree, 0, 80),
            'expected_graduation' => mb_substr($expectedGraduation, 0, 20),
            'awards_recognition' => mb_substr($awardsRecognition, 0, 1024),
            'other_examination_certificate' => mb_substr($otherExaminationCertificate, 0, 1024),
            'thesis_project' => mb_substr($thesisProject, 0, 1024),
            'extra_curricular' => mb_substr($extraCurricular, 0, 1024),
            'updated_by' => Auth::id(),
            'updated_time' => $updatedTime,
        ];

        if (! $applicant) {
            $payload['created_by'] = Auth::id();
            $payload['created_time'] = $createdTime;

            return self::create($payload);
        }

        $applicant->update($payload);

        return $applicant;
    }

    public static function getAllActionApplicants()
    {
        $sourceTypes = config('constants.sourceTypes');
        $sources = config('constants.sources');
        $genders = config('constants.genders');
        $japaneseBackgrounds = config('constants.japanese_backgrounds');
        $japaneseLevels = config('constants.japanese_levels');

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
                    'japanese_background' => $japaneseBackgrounds[$a->japanese_background] ?? '',
                    'japanese_level' => $japaneseLevels[$a->japanese_level] ?? '',
                    'background_remarks' => $a->background_remarks,
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

    public function programmingLanguages()
    {
        return $this->hasMany(ActionApplicantProgrammingLanguage::class, 'action_applicant_id', 'id');
    }

    public function skills()
    {
        return $this->hasMany(ActionApplicantSkill::class, 'action_applicant_id', 'id');
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
            'japanese_background',
            'japanese_level',
            'background_remarks',
        ];

        foreach ($fieldsToUpdate as $field) {
            if (array_key_exists($field, $data)) {
                $this->$field = $data[$field];
            }
        }

        // Update audit fields
        $this->updated_by = $userId;
        $this->updated_time = now();


        return $this->save();

    }

    /**
     * Get eligible applicants for a specific batch
     */
    public static function getEligibleApplicantsForBatch($batchId)
    {
        return self::select(
            'action_applicants.id as value',
            'action_applicants.age',
            'action_applicants.degree',
            DB::raw("CONCAT(action_applicants.first_name, ' ', action_applicants.last_name, ' (', action_applicants.email_address, ')') as label")
        )
            ->whereNotExists(function ($query) use ($batchId) {
                $query->select(DB::raw(1))
                    ->from('action_applicant_applications')
                    ->whereColumn('action_applicant_applications.action_applicant_id', 'action_applicants.id')
                    ->where('action_applicant_applications.action_batch_id', $batchId);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('action_applicant_applications')
                    ->whereColumn('action_applicant_applications.action_applicant_id', 'action_applicants.id')
                    ->where('action_applicant_applications.created_time', '>=', now()->subDays(180))
                    ->where(function ($q) {
                        $q->whereIn('action_applicant_applications.exam_application_status', [6, 7])
                            ->orWhere('action_applicant_applications.initial_interview_result', 3)
                            ->orWhere('action_applicant_applications.final_interview_result', 3)
                            ->orWhereIn('action_applicant_applications.job_offer_status', [4, 5, 6]);
                    });
            })
            ->orderBy('action_applicants.last_name')
            ->orderBy('action_applicants.first_name')
            ->get();
    }

    /**
     * Relationships
     */
    public function applications()
    {
        return $this->hasMany(ActionApplication::class, 'action_applicant_id', 'id');
    }

    public function latestApplication()
    {
        return $this->hasOne(ActionApplication::class, 'action_applicant_id', 'id')
            ->latest('created_time');
    }

    // functions used for applications
    public function resolveExamCategory(): string
    {
        $age = (int) $this->age;

        if ($age >= 25) {
            return 'adult';
        }

        $rawDegree = ! empty($this->others_degree)
            ? $this->others_degree
            : $this->degree;

        $normalizedDegree = static::normalizeDegree((string) $rawDegree);

        return static::isTechDegree($normalizedDegree) ? 'young_it' : 'young_other';
    }

    public static function normalizeDegree(string $degree): string
    {
        $degree = strtolower(trim($degree));
        $degree = preg_replace('/[^a-z0-9\s]/', ' ', $degree);
        $degree = preg_replace('/\s+/', ' ', $degree);

        return $degree;
    }

    public static function isTechDegree(string $degree): bool
    {
        $patterns = config('constants.tech_degree_patterns', []);

        foreach ($patterns as $pattern) {
            $normalizedPattern = static::normalizeDegree((string) $pattern);

            if ($normalizedPattern === '') {
                continue;
            }

            if (in_array($normalizedPattern, ['it', 'cs', 'cpe', 'bsit', 'bscs', 'bsce'], true)) {
                if (preg_match('/\b'.preg_quote($normalizedPattern, '/').'\b/', $degree)) {
                    return true;
                }

                continue;
            }

            if (str_contains($degree, $normalizedPattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a new applicant
     */
    public static function createApplicant(array $data)
    {
        if (($data['japanese_background'] ?? null) != 3) {
            $data['japanese_level'] = null;
        }

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
        if (! $email) {
            throw new \Exception('Email address is required for upsert.');
        }

        $applicant = self::where('email_address', $email)->first();

        if ($applicant) {
            // During update, exclude source-related fields
            $updateData = array_diff_key($data, array_flip([
                'source_type',
                'source',
                'other_source',
            ]));

            return $applicant->updateApplicant($updateData);
        }

        return self::createApplicant($data);
    }
}
