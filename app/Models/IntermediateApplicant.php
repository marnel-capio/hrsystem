<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntermediateApplicant extends Model
{
    use HasFactory;

    protected $table = 'intermediate_applicants';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
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
        'birthdate',
        'age',
        'address',
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
        'updated_time'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'registered_date' => 'datetime',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
        'age' => 'integer',
        'gender' => 'integer',
        'source_type' => 'integer',
        'source' => 'integer',
        'children' => 'integer',
    ];

    /**
     * RELATIONSHIPS
     */

    // Applications this applicant applied to
    public function applications(): HasMany
    {
        return $this->hasMany(IntermediateApplication::class, 'intermediate_applicant_id');
    }

    // Who registered this applicant
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    // Who created this record
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Who updated this record
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * ACCESSORS (HELPER METHODS)
     */

    // Full name
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    // Gender label
    public function getGenderLabelAttribute(): string
    {
        return $this->gender == 1 ? 'Male' : 'Female';
    }

    // Source type label
    public function getSourceTypeLabelAttribute(): string
    {
        $labels = [
            1 => 'Service Provider (SP)',
            2 => 'Recruitment Portals',
            3 => 'Employee Referral',
            4 => 'Walk-in'
        ];
        return $labels[$this->source_type] ?? 'Unknown';
    }

    // Source label
    public function getSourceLabelAttribute(): string
    {
        $labels = [
            // Recruitment Portals
            1 => 'Mynimo', 2 => 'Indeed', 3 => 'Kalibrr', 4 => 'FoundIt',
            5 => 'LinkedIn', 6 => 'Facebook', 7 => 'Jobstreet',
            // Service Providers
            8 => 'AAISI', 9 => 'Primover', 10 => 'Pan Asia',
            11 => 'Nityo', 12 => 'CPS'
        ];
        return $labels[$this->source] ?? ($this->other_source ?? 'Unknown');
    }

    // Age from birthdate (if needed)
    public function getCalculatedAgeAttribute(): int
    {
        if ($this->birthdate) {
            return now()->diffInYears($this->birthdate);
        }
        return $this->age ?? 0;
    }

    /**
     * SCOPES
     */

    // Scope for active applicants
    public function scopeActive($query)
    {
        return $query->where('email_address', '!=', '')->whereNotNull('email_address');
    }

    // Scope by source type
    public function scopeBySourceType($query, $sourceType)
    {
        return $query->where('source_type', $sourceType);
    }

    // Scope by age range
    public function scopeAgeBetween($query, $minAge, $maxAge)
    {
        return $query->whereBetween('age', [$minAge, $maxAge]);
    }

    /**
     * QUERY HELPERS
     */

    // Get applicant with full name search
    public static function searchByName($search)
    {
        return self::whereRaw('CONCAT(first_name, " ", COALESCE(middle_name, ""), " ", last_name) LIKE ?', ["%{$search}%"])
                  ->orWhere('email_address', 'like', "%{$search}%");
    }

    // Count applicants by source type
    public static function countBySourceType()
    {
        return self::selectRaw('source_type, COUNT(*) as count')
                  ->groupBy('source_type')
                  ->pluck('count', 'source_type');
    }

    /**
     * BOOT METHOD (Timestamps)
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($applicant) {
            $applicant->created_by = auth()->id() ?? 1;
            $applicant->created_time = now();
        });

        static::updating(function ($applicant) {
            $applicant->updated_by = auth()->id() ?? 1;
            $applicant->updated_time = now();
        });
    }

    public function workExperiences()
    {
        return $this->hasMany(IntermediateApplicationWorkExperience::class, 'intermediate_applicant_id');
    }

    public function skills()
    {
        return $this->hasMany(IntermediateApplicantSkill::class, 'intermediate_applicant_id');
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
