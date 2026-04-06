<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntermediateApplicant extends Model
{
    use HasFactory;

    protected $table = 'intermediate_applicants';

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
        'birthdate' => 'date',
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
}