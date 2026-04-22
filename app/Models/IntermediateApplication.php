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

    protected $fillable = [
        'application_stage', 'intermediate_applicant_id', 'resource_schedule_id', 'fy_week',
        'position', 'source_project_id','upload_resume', 'upload_pic', 'answer_q1', 'answer_q2', 'answer_q3', 'answer_q4',
        'answer_q5', 'answer_q6', 'answer_q7', 'availability_date', 'desired_salary_range',
        'work_preference', 'basic_pay', 'bonuses', 'hmo', 'leaves', 'allowances',
        'other_benefits', 'targeted_company', 'industry_experience', 'current_employer',
        'asking_rate', 'site_assignment', 'testing_datetime', 'atpp', 'tech_exam',
        'hr_interview_datetime', 'hr_interview_week', 'bu_interview_datetime',
        'bu_interview_week', 'final_interview_datetime', 'final_interview_week',
        'paper_screening_status', 'exam_status', 'hr_interview_status', 'bu_interview_status',
        'final_interview_status', 'job_offer_status', 'job_offer_accepted_date',
        'aws_start_date', 'aws_rank', 'parked_to', 'reason_by_category', 'reason_for_decline',
        'remarks', 'contacted_by', 'contacted_date', 'replied', 'replied_date',
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'application_stage' => 'integer',
        'answer_q1' => 'integer', 'answer_q2' => 'integer', 'answer_q3' => 'integer',
        'answer_q4' => 'integer', 'answer_q5' => 'integer', 'answer_q6' => 'integer',
        'answer_q7' => 'integer',
        'testing_datetime' => 'datetime',
        'hr_interview_datetime' => 'datetime',
        'bu_interview_datetime' => 'datetime',
        'final_interview_datetime' => 'datetime',
        'job_offer_accepted_date' => 'datetime',
        'aws_start_date' => 'datetime',
        'contacted_date' => 'datetime',
        'replied_date' => 'datetime',
        'paper_screening_status' => 'integer',
        'exam_status' => 'integer',
        'hr_interview_status' => 'integer',
        'bu_interview_status' => 'integer',
        'final_interview_status' => 'integer',
        'job_offer_status' => 'integer',
        'replied' => 'integer',
        'atpp' => 'decimal:2',
        'tech_exam' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function intermediateApplicant(): BelongsTo
    {
        return $this->belongsTo(IntermediateApplicant::class);
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

    /**
     * Accessors using Laravel 9+ Attribute syntax
     */
    protected function stageLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getStageLabel()
        );
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

    protected function projectName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->project?->project_name
        );
    }

    /**
     * Status label helpers
     */
    private function getStageLabel(): string
    {
        return match ($this->application_stage) {
            1 => 'New',
            2 => 'For Exam',
            3 => 'For Initial Interview',
            4 => 'For Final Interview',
            5 => 'For Job Offer',
            default => 'Unknown'
        };
    }

    private function getStatusLabel(int $status): string
    {
        return match ($status) {
            1 => 'Pending',
            2 => 'Done',
            3 => 'Passed',
            4 => 'P2',
            5 => 'Failed',
            default => 'N/A'
        };
    }

    private function getJobOfferLabel(int $status): string
    {
        return match ($status) {
            1 => 'Pending',
            2 => 'Done',
            3 => 'Accept',
            4 => 'Decline',
            5 => 'Withdraw',
            6 => 'Retracted',
            default => 'N/A'
        };
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
            $q->whereHas('intermediateApplicant', function ($subQuery) use ($search) {
                $subQuery->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ["%{$search}%"])
                         ->orWhere('email_address', 'like', "%{$search}%");
            })->orWhere('position', 'like', "%{$search}%")
              ->orWhereHas('project', fn($subQuery) =>
                  $subQuery->where('project_name', 'like', "%{$search}%")
              );
        });
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_time', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Dashboard statistics
     */
    public static function dashboardStats(): array
    {
        return [
            'total' => self::count(),
            'by_stage' => self::selectRaw('application_stage, COUNT(*) as count')
                ->groupBy('application_stage')
                ->pluck('count', 'application_stage')
                ->toArray(),
            'passed_exams' => self::where('exam_status', 3)->count(),
            'job_offers' => self::where('job_offer_status', 3)->count(),
        ];
    }

    /**
     * Paginated results with filters
     */
    public static function paginated(array $filters = [], int $perPage = 20)
    {
        $query = self::with([
            'intermediateApplicant:id,first_name,last_name,email_address',
            'project:id,project_name'
        ]);

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (isset($filters['stage'])) {
            $query->byStage($filters['stage']);
        }

        if (isset($filters['project_id'])) {
            $query->byProject($filters['project_id']);
        }

        return $query->orderBy('created_time', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Boot method for automatic timestamp and user tracking
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $application) {
            $application->created_by = auth()->id() ?? 1;
        });

        static::updating(function (self $application) {
            $application->updated_by = auth()->id() ?? 1;
        });
    }

protected function fullApplicantName(): Attribute
{
    return Attribute::make(
        get: fn () => $this->intermediateApplicant?->full_name ?? 'Unknown'
    );
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
