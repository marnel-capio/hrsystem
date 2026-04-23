<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class IntermediateApplication extends Model
{
    use HasFactory;

    protected $table = 'intermediate_applicants_applications';

    public $timestamps = true;
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        // Core
        'application_stage',
        'intermediate_applicant_id',
        'resource_schedule_id',
        'fy_week',
        'position',
        'source_project_id',

        // Files
        'upload_resume',
        'upload_pic',

        // Screening answers
        'answer_q1', 'answer_q2', 'answer_q3', 'answer_q4',

        // Profile / preference
        'availability_date',
        'desired_salary_range',
        'work_preference',
        'basic_pay',
        'bonuses',
        'hmo',
        'leaves',
        'allowances',
        'other_benefits',
        'targeted_company',
        'industry_experience',
        'current_employer',
        'asking_rate',
        'site_assignment',

        // Screening
        'paper_screening_status',

        // Exam (new structure)
        'exam_plan_date',
        'exam_actual_date',
        'exam_venue',
        'exam_atpp_part1_correct',
        'exam_atpp_part1_wrong',
        'exam_atpp_part2_correct',
        'exam_atpp_part2_wrong',
        'exam_atpp_part3_correct',
        'exam_atpp_part3_wrong',
        'exam_atpp_result',
        'exam_tech_result',
        'exam_result',
        'exam_application_status',
        'exam_remarks',

        // Initial Interview
        'initial_interview_plan_date',
        'initial_interview_actual_date',
        'initial_interview_venue',
        'initial_interview_final',
        'initial_interview_result',
        'initial_interview_application_status',
        'initial_interview_remarks',

        // Final Interview
        'final_interview_date',
        'final_interview_final',
        'final_interview_result',
        'final_interview_application_status',
        'final_interview_remarks',

        // Job offer
        'job_offer_schedule',
        'job_offer_status',
        'job_offer_remarks',

        // Post offer
        'aws_start_date',
        'aws_rank',
        'parked_to',
        'reason_by_category',
        'reason_for_decline',

        'remarks',

        // Tracking
        'contacted_by',
        'contacted_date',
        'replied',
        'replied_date',

        'created_by',
        'updated_by'
    ];

    /**
     * Casting
     */
    protected $casts = [
        'application_stage' => 'integer',

        'answer_q1' => 'boolean',
        'answer_q2' => 'boolean',
        'answer_q3' => 'boolean',
        'answer_q4' => 'boolean',

        'contacted_date' => 'datetime',
        'replied_date' => 'datetime',

        'exam_plan_date' => 'datetime',
        'exam_actual_date' => 'datetime',
        'initial_interview_plan_date' => 'datetime',
        'initial_interview_actual_date' => 'datetime',
        'final_interview_date' => 'datetime',
        'job_offer_schedule' => 'datetime',
        'aws_start_date' => 'datetime',

        'paper_screening_status' => 'integer',
        'exam_result' => 'integer',
        'exam_application_status' => 'integer',
        'initial_interview_result' => 'integer',
        'initial_interview_application_status' => 'integer',
        'final_interview_result' => 'integer',
        'final_interview_application_status' => 'integer',
        'job_offer_status' => 'integer',
        'replied' => 'integer',

        'exam_atpp_result' => 'decimal:2',
        'exam_tech_result' => 'decimal:2',
        'initial_interview_final' => 'decimal:2',
        'final_interview_final' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function intermediateApplicant(): BelongsTo
    {
        return $this->belongsTo(IntermediateApplicant::class, 'intermediate_applicant_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(IntermediateProjectModel::class, 'source_project_id');
    }

    public function contactedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contacted_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected function statusLabels(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'paper_screening' => $this->getStatusLabel($this->paper_screening_status),
                'exam' => $this->getStatusLabel($this->exam_status),
                'hr_interview' => $this->getStatusLabel($this->hr_interview_status),
                'bu_interview' => $this->getStatusLabel($this->bu_interview_status),
                'final_interview' => $this->getStatusLabel($this->final_interview_status),
                'job_offer' => $this->getJobOfferLabel($this->job_offer_status),
            ]
        );
    }

    protected $appends = [
    'fullApplicantName',
    'projectName',
    'stageLabel'
];

    protected function fullApplicantName(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                $this->applicant
                    ? "{$this->applicant->first_name} {$this->applicant->last_name}"
                    : 'Unknown'
        );
    }

    protected function projectName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->project?->project_name
        );
    }

    protected function stageLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->application_stage) {
                1 => 'New',
                2 => 'For Exam',
                3 => 'For HR Interview',
                4 => 'For BU Interview',
                5 => 'For Final Interview',
                6 => 'For Job Offer',
                7 => 'Failed',
                default => 'Unknown'
            }
        );
    }

    /**
     * Scopes
     */
    public function scopeByStage(Builder $query, int $stage): Builder
    {
        return $query->where('application_stage', $stage);
    }

    public function scopeByProject(Builder $query, int $projectId): Builder
    {
        return $query->where('source_project_id', $projectId);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('applicant', function ($sub) use ($search) {
                $sub->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$search}%"])
                    ->orWhere('email_address', 'like', "%{$search}%");
            })
            ->orWhere('position', 'like', "%{$search}%")
            ->orWhereHas('project', fn ($p) =>
                $p->where('project_name', 'like', "%{$search}%")
            );
        });
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_time', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Dashboard stats
     */
    public static function dashboardStats(): array
    {
        return [
            'total' => self::count(),
            'by_stage' => self::selectRaw('application_stage, COUNT(*) as count')
                ->groupBy('application_stage')
                ->pluck('count', 'application_stage')
                ->toArray(),

            'passed_exams' => self::where('exam_result', 2)->count(),
            'job_offers' => self::where('job_offer_status', 3)->count(),
        ];
    }

    /**
     * Boot
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id() ?? 1;
            $model->updated_by = auth()->id() ?? 1;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id() ?? 1;
        });
    }

public function getFullApplicantNameAttribute()
{
    return $this->intermediateApplicant?->full_name ?? 'Unknown';
}

public function getProjectNameAttribute()
{
    return $this->project?->project_name;
}

public function getStageLabelAttribute()
{
    return $this->getStageLabel();
}

}
